<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Exports;
use Excel;
use App\Exports\ExportProgramsOverview;
use App\Exports\ExportProgram;
use App\Program;
use App\User;
use Zipper;

class ExportReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportParams;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->reportParams = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = "exports/{$this->reportParams['id']}";
        $programs = Program::all();
        $totalSteps = $programs->count() + 1;

        $user = User::where('meta->report->id', $this->reportParams['id'])->first();

        $user->updateReport(1, 'Gerando o relatório de Programas', 0, $totalSteps);
        Excel::store(
            new ExportProgramsOverview( $this->reportParams['month'], $this->reportParams['year']),
            "$path/00-Programas.xlsx" 
        );

        $ct = 0;
        foreach ($programs as $p) {
            $ct++;
            $user->updateReport(1, "Gerando relatório {$p->name}", $ct, $totalSteps);
            $num = str_pad($p->id, 2, '0', STR_PAD_LEFT);
            Excel::store(
                new ExportProgram( $p->id, $this->reportParams['month'], $this->reportParams['year']),
                "$path/$num-{$p->name}.xlsx"
            );
        }

        $user->updateReport(2, "Gerando arquivo .zip", $totalSteps - 1, $totalSteps);
        $month = str_pad($this->reportParams['month'], 2, '0', STR_PAD_LEFT);
        $fileName = "$path/Relatorios_$month-{$this->reportParams['year']}.zip";
        $zipPath = storage_path("app/$fileName");
        $files = glob(storage_path("app/$path/*"));
        Zipper::make($zipPath)
            ->add($files)
            ->close();
        array_map('unlink', $files);

        $user->updateReportFile($fileName);
        $user->updateReport(3, "Concluído!", $totalSteps, $totalSteps);
    }
}
