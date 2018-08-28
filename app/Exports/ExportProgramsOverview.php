<?php

namespace App\Exports;

use App\Program;


use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportProgramsOverview implements FromView, WithTitle, WithEvents
{
    public $month;
    public $year;
    public $count;

    /**
    * @return \Illuminate\Database\Query\Builder
    */
   public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function registerEvents(): array
    {
        $helper = $this;
        return [
            AfterSheet::class => function(AfterSheet $event)  use($helper)
            {
                // Header Style
                $event->sheet->getStyle('A1:N2')
                    ->applyFromArray(
                        [
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true
                            ],
                        ]
                    );

                $event->sheet->getStyle('B2:M2')
                    ->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            ]
                        ]
                    );
                

                // Active Radios Style
                $event->sheet->getStyle('B1:G2')
                    ->applyFromArray(
                        [
                            'borders' => [
                                'right' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                                'left' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                                'inside' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FFe1e1e1'],
                                ]
                            ],   
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFB6DEE7'
                                ]
                            ]
                        ]
                    );

                $event->sheet->getStyle('B3:G' . (2+ $helper->count))
                    ->applyFromArray(
                        [
                            'borders' => [
                                'right' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                                'left' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                                'inside' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FFe1e1e1'],
                                ]
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFDAEEF3'
                                ]
                            ]
                        ]
                    );

                 // Idle Radios Style
                 $event->sheet->getStyle('H1:M2')
                 ->applyFromArray(
                     [
                         'borders' => [
                             'right' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FF000000'],
                             ],
                             'left' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FF000000'],
                             ],
                             'inside' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FFe1e1e1'],
                             ]
                         ],   
                         'fill' => [
                             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                             'startColor' => [
                                 'argb' => 'FFfcd6b4'
                             ]
                         ]
                     ]
                 );

             $event->sheet->getStyle('H3:M' . (2+ $helper->count))
                 ->applyFromArray(
                     [
                         'borders' => [
                             'right' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FF000000'],
                             ],
                             'left' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FF000000'],
                             ],
                             'inside' => [
                                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                 'color' => ['argb' => 'FFe1e1e1'],
                             ]
                         ],
                         'fill' => [
                             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                             'startColor' => [
                                 'argb' => 'FFfde9d9'
                             ]
                         ]
                     ]
                 );
                $event->sheet->getColumnDimension('A')->setAutoSize(true);

            }
        ];
    }

    public function view(): View
    {
        $this->count = Program::count();

        return view('exports.programs', [
            'programs' => Program::all(),
            'helper' => $this
        ]);
    }

    public function map($p)
    {
        $radio_types = [ 'F', 'W', 'A', 'T', 'V' ];
        $helper = $this;
        $totalClients = [];
        $activeClients = [];
        $idleClients = [];
        $activeSignatures = [];
        $inactiveSignatures = [];
        $downloads = $p->downloads()->count();


        foreach ($radio_types as $type) {
            $totalClients[$type] = $p->clients()->whereRadioType($type)->count();
            $activeClients[$type] = $p->clients()->whereRadioType($type)
                ->whereHas('downloads', function ($q) use($helper)
                { 
                    return $q->whereMonth('download_date', $helper->month)
                        ->whereYear('download_date', $helper->year); 
                })
                ->count();
            $idleClients[$type] = $totalClients[$type] - $activeClients[$type];

            // $activeSignatures[$type] = $p->signatures()
            //     ->whereStatus('A')
            //     ->whereHas('client', function($q) use($type){
            //         return $q->whereRadioType($type);
            //     })
            //     ->count();

            // $inactiveSignatures[$type] = $p->signatures()
            //     ->whereStatus('D')
            //     ->whereHas('client', function($q) use($type){
            //         return $q->whereRadioType($type);
            //     })
            //     ->count();
        }

        $r = [
            'name' => $p->name,
            'active_radios' => $activeClients,
            'idle_radios'   => $idleClients,
            'active_signatures' => $activeSignatures,
            'inactive_signatures' => $inactiveSignatures,
            'downloads' => $downloads,
        ];
        return $r;
        //return [ 'name' => $p->name, 'downloads' => 10 ];
    }

    public function title(): string
    {
        return $this->month . '-' . $this->year;
    }

    
}
