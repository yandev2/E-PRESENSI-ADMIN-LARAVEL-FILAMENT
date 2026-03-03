<?php

namespace App\Filament\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanExporter implements FromView, WithStyles, WithEvents
{
    protected $data;

    public function __construct($json)
    {
        $this->data = $json;
    }
    public function view(): View
    {
        return view('component.exporter.KaryawanExporterExel', [
            'json' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet) {}

    public function registerEvents(): array
    {
        return [];
    }
}
