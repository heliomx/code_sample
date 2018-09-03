<?php

namespace App\Exports;

use App\Program;
use App\Client;


use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportProgramSheet implements FromView, WithTitle, WithEvents
{
    public $month;
    public $year;
    public $count;
    public $id;
    public $active;

    private $types = [
        'F' => 'Rádio FM',
        'W' => 'Rádio Web',
        'A' => 'Rádio AM',
        'T' => 'TV',
        'V' => 'TV Web'
    ];

    /**
    * @return \Illuminate\Database\Query\Builder
    */
   public function __construct(bool $active, int $id, int $month, int $year)
    {
        $this->month = $month;
        $this->year  = $year;
        $this->id = $id;
        $this->active = $active;
    }
    // getHighestDataRow=
    public function registerEvents(): array
    {
        $helper = $this;
        return [
            AfterSheet::class => function(AfterSheet $event)  use($helper)
            {
                // Header Style
                $event->sheet->getStyle('A1')
                    ->applyFromArray(
                        [
                            'font' => [
                                'bold' => true,
                                'size' => 18
                            ],
                        ]
                    );

                $event->sheet->getStyle('A2:I2')
                    ->applyFromArray(
                        [
                            'borders' => [
                                'inside' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FFe1e1e1'],
                                ]
                            ],   
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFf1c233'
                                ]
                            ]
                        ]
                    );

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);

                $event->sheet->setAutoFilter('D2:F' . $event->sheet->getHighestDataRow());

            }
        ];
        return [];
    }

    public function view(): View
    {
        $helper = $this;

        if ($this->active)
        {
            $clients = Client::whereStatus(Client::STATUS_ACTIVE)
            ->whereHas('programs', function($q) use($helper) {
                return $q->whereProgramId($helper->id);
            })
            ->withCount(
                ['downloads' => function($q) use ($helper)
                {
                    return $q->where('programs.id', $helper->id)
                        ->whereMonth('download_date', $helper->month)
                        ->whereYear('download_date', $helper->year)
                        ->join('program_files', 'downloads.program_file_id', '=', 'program_files.id')
                        ->join('programs', 'program_files.program_id', '=', 'programs.id') ;
                }]
            )->havingRaw('downloads_count > 0')->get();
            //dd($clients);
        } else {
            $clients = Client::whereStatus(Client::STATUS_ACTIVE)
            ->whereHas('programs', function($q) use($helper) {
                return $q->whereProgramId($helper->id);
            })
            ->withCount(
                ['downloads' => function($q) use ($helper)
                {
                    return $q->where('programs.id', $helper->id)
                        ->whereMonth('download_date', $helper->month)
                        ->whereYear('download_date', $helper->year)
                        ->join('program_files', 'downloads.program_file_id', '=', 'program_files.id')
                        ->join('programs', 'program_files.program_id', '=', 'programs.id') ;
                }]
            )->havingRaw('downloads_count = 0')->get();
        }
        
        return view('exports.program', [
            'clients' => $clients,
            'program' => Program::find($this->id),
            'helper' => $this
        ]);
    }
    
    public function formatMobile($tel)
    {
        if(  preg_match( '/^(\d{2})(\d)(\d{4})(\d{4})$/', $tel,  $matches ) )
        {
            $result = "($matches[1]) $matches[2] $matches[3]-$matches[4]";
            return $result;
        }
    }

    public function formatTel($tel)
    {
        if(  preg_match( '/^(\d{2})(\d{4})(\d{4})$/', $tel,  $matches ) )
        {
            $result = "($matches[1]) $matches[2]-$matches[3]";
            return $result;
        }
    }

    public function radioType($radio_type)
    {
        return $this->types[$radio_type];
    }

    public function title(): string
    {
        return $this->active ? 'Ativos' : 'Ociosos';
    }

    
}
