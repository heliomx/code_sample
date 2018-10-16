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
use Exception;

class Package extends Model
{
    const STATUS_UNPACKING = 'U';
    const STATUS_PROCESSING_PROGRAM_FILES = 'P';
    const STATUS_DONE = 'D';
    const STATUS_ERROR = 'E';

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

        $initSuccess = true;
        
        try {
            Log::info('metadata', ['data' => $upload->metadata['publish_on']]);
            preg_match('/(\d+)\/(\d+)\/(\d+)/', $upload->metadata['publish_on'], $date);
            $publish_start = Carbon::createFromDate($date[3], $date[2], $date[1]);

            $package->upload()->associate($upload);
            $package->status = self::STATUS_UNPACKING;
            $package->save();

            Storage::disk('packages')->makeDirectory($package->id);
            $path = storage_path("app/packages/$package->id");
            Zipper::make($upload->path())->extractTo($path);
            $directories = Storage::disk('packages')->directories($package->id);
            Log::info($directories);
            
            $dir_pattern = '/(.+?)_(\d+)$/';
            $file_pattern = '/(.+?)_(\d+(?:-\d+)?)_(\d+)_(\d+)_(\d+)\.(.+)$/';
            
            $errors = [];

            $package->status = self::STATUS_PROCESSING_PROGRAM_FILES;
            $meta['folders'] = count($directories);
            $package->meta = $meta;
            $package->save();
        } catch (Exception $e) {
            Log::error("Ocorreu um erro inicializando o processamento do ZIP.");
            $package->status = self::STATUS_ERROR;
            Log::error($e->getMessage());
            $initSuccess = false;
            array_push($meta['errors'], "Ocorreu um erro inicializando o processamento do ZIP.");
            array_push($meta['errors'], $e->getMessage());
        }

        if ($initSuccess)
        {
            for ($k = 0; $k < count($directories); $k++)
            {
                try
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

                                    if ($k == 1) throw new Exception('Teste de erro');

                                    $pfile->file_name = "$dir_match[0]/$fname";
                                    $pfile->publish_start = $publish_start;
                                    $pfile->publish_end = (new Carbon($publish_start))->addDays($program->publication_days);
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
                        
                        
                    } else {
                        array_push($meta['errors'], "Nome de diretório inválido: $directories[$k]");
                    }
                } catch(Exception $e) {
                    
                    Log::error("Ocorreu um erro processando a pasta {$directories[$k]}.");
                    Log::error($e->getMessage());
                    array_push($meta['errors'], "Ocorreu um erro processando a pasta {$directories[$k]}.");
                    array_push($meta['errors'], $e->getMessage());
                } finally {
                    $package->meta = $meta;
                    $package->save();
                }
                
            }
            try{
                unlink($upload->path());
                $package->status = self::STATUS_DONE;
                ProgramPublication::check();
                Log::info("Zip {$upload->path()} apagado");
            } catch( Exception $e) {
                Log::error("Ocorreu um erro finalizando o upload");
                Log::error($e->getMessage());
                array_push($meta['errors'], "Ocorreu um erro finalizando o upload");
                array_push($meta['errors'], $e->getMessage());
            } finally {
                $package->meta = $meta;
                $package->save();
            }
            
        }
        else
        {
            try{
                unlink($upload->path());
                Self::recursiveRemoveDirectory($path);
                $package->meta = $meta;
                Log::info("Zip {$upload->path()} apagado");
                Log::info("Package $package->id apagado");
            } catch(Exception $e) {
                Log::error("Ocorreu um erro finalizando o upload");
                Log::error($e->getMessage());
                array_push($meta['errors'], "Ocorreu um erro finalizando o upload");
                array_push($meta['errors'], $e->getMessage());
            } finally {
                $package->meta = $meta;
                $package->save();
            }
        }

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
