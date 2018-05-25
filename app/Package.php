<?php

namespace App;

use OneOffTech\TusUpload\TusUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Lib\ProgramPublication;
use App\Program;
use App\ProgramFile;
use Carbon\Carbon;
use File;
use Log;
use Zipper;

class Package extends Model
{
    const STATUS_UNPACKING = 'U';
    const STATUS_PROCESSING_PROGRAM_FILES = 'P';
    const STATUS_DONE = 'D';

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'upload_id', 'meta', 'created_at', 'updated_at'
    ];

    protected $dates = [ 'publish_start', 'publish_end', 'updated_at', 'created_at' ];
  
    public static function open(TusUpload $upload)
    {
        $package = new static();
        
        $meta = [
            'log' => [],
            'errors' => [],
            'programs_updated' => [],
            'program_files_created' => [],
            'program_files_updated' => [],
            'folders' => 0,
            'folders_processed' => 0,
        ];
        

        Log::info('metadata', ['data' => $upload->metadata['publish_on']]);
        preg_match('/(\d+)\/(\d+)\/(\d+)/', $upload->metadata['publish_on'], $date);
        $publish_start = Carbon::createFromDate($date[3], $date[2], $date[1]);
        $publish_end = (new Carbon($publish_start))->addWeek();

        $package->upload()->associate($upload);
        $package->status = self::STATUS_UNPACKING;
        $package->save();

        Storage::disk('packages')->makeDirectory($package->id);
        $path = storage_path("app/packages/$package->id");
        Zipper::make($upload->path())->extractTo($path);
        $directories = Storage::disk('packages')->directories($package->id);
        Log::info($directories);
        
        $dir_pattern = '/(.+?)_(\d+)$/';
        $file_pattern = '/(.+?)_(\d+)_(\d+)_(\d+)_(\d+)\.(.+)$/';
        
        $errors = [];

        $package->status = self::STATUS_PROCESSING_PROGRAM_FILES;
        $meta['folders'] = count($directories);
        $package->meta = $meta;
        $package->save();

        for ($k = 0; $k < count($directories); $k++)
        {
            if (preg_match($dir_pattern, $directories[$k], $dir_match))
            {
                $program = Program::find($dir_match[2]);
                if ($program)
                {
                    array_push($meta['programs_updated'], $program->id);

                    $subDirectories = glob(storage_path("app/packages/$dir_match[0]/*"));
                    Log::info($subDirectories);
                    for ($j = 0; $j < count($subDirectories); $j++)
                    {
                        $zipname = str_replace('.mp3', '', $subDirectories[$j]);
                        Zipper::make("$zipname.zip")
                            ->add($subDirectories[$j])
                            ->close();
                            
                        if (is_dir($subDirectories[$j]))
                        {
                            Self::recursiveRemoveDirectory($subDirectories[$j]);
                        } else {
                            unlink($subDirectories[$j]);
                        }
                    }

                    $files = File::allFiles(storage_path("app/packages/$dir_match[0]"));
                    for ($i = 0; $i < count($files); $i++)
                    {
                        $fname = $files[$i]->getFileName();
                        if( preg_match($file_pattern, $fname, $f_match))
                        {
                            $pfile = ProgramFile::firstOrNew(
                                [
                                    'program_number' => $f_match[2], 
                                    'program_id' => $program->id
                                ]);
                            $created = empty($pfile->id);
                            $pfile->program()->associate($program);
                            $pfile->package()->associate($package);
                            $pfile->file_name = "$dir_match[0]/$fname";
                            $pfile->publish_start = $publish_start;
                            $pfile->publish_end = $publish_end;
                            $pfile->status = ProgramFile::STATUS_WAITING;
                            $pfile->air_on = Carbon::createFromDate($f_match[5], $f_match[4], $f_match[3]);
                            $pfile->save();

                            if ($created)
                            {
                                array_push($meta['program_files_created'], $pfile->id);
                            } else {
                                array_push($meta['program_files_updated'], $pfile->id);
                            }
                            array_push($meta['log'], "Arquivo de programação indexado: $fname");
                        } else {
                            array_push($meta['errors'], "Nome de arquivo inválido: $fname");
                        }
                    }
                } else {
                    array_push($meta['errors'], "ID de programa inválido no diretório: $directories[$k]");
                }

                $meta['folders_processed']++;
                $package->meta = $meta;
                $package->save();
                
            } else {
                array_push($meta['errors'], "Nome de diretório inválido: $directories[$k]");
            }
            
        }
        unlink($upload->path());
        $package->meta = $meta;
        $package->status = self::STATUS_DONE;
        $package->save();
        ProgramPublication::check();
        Log::info('Package Status:', ['meta' => $package->meta]);

    }

    private static function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) { 
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

    public function upload(){
        return $this->belongsTo('OneOffTech\TusUpload\TusUpload', 'upload_id');
    }
}
