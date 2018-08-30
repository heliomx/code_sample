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
                $event->sheet->getStyle('A1:O2')
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

                $event->sheet->getStyle('C2:N2')
                    ->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                            ]
                        ]
                    );
                

                // Active Radios Style
                $event->sheet->getStyle('C1:H2')
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

                $event->sheet->getStyle('C3:H' . (2+ $helper->count))
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
                 $event->sheet->getStyle('I1:N2')
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

             $event->sheet->getStyle('I3:N' . (2+ $helper->count))
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
        $total = 0;
        $activeClients = [];
        $idleClients = [];
        $activeSignatures = [];
        $inactiveSignatures = [];
        $downloads = $p->downloads()->count();


        foreach ($radio_types as $type) {
            $totalClients[$type] = $p->clients()->where('clients.status', '=', Client::STATUS_ACTIVE)->whereRadioType($type)->count();
            $activeClients[$type] = $p->clients()->where('clients.status', '=', Client::STATUS_ACTIVE)->whereRadioType($type)
                ->withCount(
                    ['downloads' => function($q) use ($helper, $p)
                    {
                        return $q->where('programs.id',$p->id)
                            ->whereMonth('download_date', $helper->month)
                            ->whereYear('download_date', $helper->year)
                            ->join('program_files', 'downloads.program_file_id', '=', 'program_files.id')
                            ->join('programs', 'program_files.program_id', '=', 'programs.id') ;
                    }]
                )->havingRaw('downloads_count > 0')
                ->get()->count();
                
            $idleClients[$type] = $totalClients[$type] - $activeClients[$type];
            $total += $totalClients[$type];
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
            'total' => $total,
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
