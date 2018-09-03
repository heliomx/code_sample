<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportProgram implements WithMultipleSheets
{
    use Exportable;

    public $month;
    public $year;
    public $id;
    public $count;
    
    public function __construct(int $id, int $month, int $year)
    {
        $this->month = $month;
        $this->year  = $year;
        $this->id    = $id;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new ExportProgramSheet(true, $this->id, $this->month, $this->year),
            new ExportProgramSheet(false, $this->id, $this->month, $this->year),
        ];

        return $sheets;
    }
}