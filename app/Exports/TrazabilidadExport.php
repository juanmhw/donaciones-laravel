<?php

namespace App\Exports;

use App\Models\TrazabilidadItem;
use App\Models\Campania;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; // OBLIGATORIO
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// NOTA: Quitamos "ShouldAutoSize" porque rompe el diseño con los encabezados grandes
class TrazabilidadExport implements FromView, WithStyles, WithEvents
{
    protected $filtros;

    public function __construct($filtros)
    {
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        $query = TrazabilidadItem::query();

        if (!empty($this->filtros['campaniaid'])) {
            $query->where('campaniaid', $this->filtros['campaniaid']);
        }
        if (!empty($this->filtros['desde'])) {
            $query->whereDate('fecha_donacion', '>=', $this->filtros['desde']);
        }
        if (!empty($this->filtros['hasta'])) {
            $query->whereDate('fecha_donacion', '<=', $this->filtros['hasta']);
        }

        // ORDENAMIENTO (Vital para que no se mezclen los almacenes)
        $items = $query->orderBy('almacen_nombre', 'asc')
                       ->orderBy('estante_codigo', 'asc')
                       ->orderBy('espacio_codigo', 'asc')
                       ->orderBy('fecha_donacion', 'desc')
                       ->get();

        $grupos = $items->groupBy(function($item) {
            return $item->almacen_nombre ?: 'SIN UBICACIÓN / EN TRÁNSITO';
        });

        $campaniaNombre = !empty($this->filtros['campaniaid']) 
            ? Campania::find($this->filtros['campaniaid'])?->titulo 
            : 'Todas las Campañas';

        return view('reportes.trazabilidad.excel', [
            'grupos' => $grupos,
            'campaniaNombre' => $campaniaNombre,
            'totalItems' => $items->count()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [ 1 => ['font' => ['bold' => true, 'size' => 16]] ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // ---------------------------------------------------------
                // 1. ANCHOS DE COLUMNA MANUALES (Para evitar los '##')
                // ---------------------------------------------------------
                $sheet->getColumnDimension('A')->setWidth(15); // Código
                $sheet->getColumnDimension('B')->setWidth(30); // Producto
                $sheet->getColumnDimension('C')->setWidth(20); // Categoría
                $sheet->getColumnDimension('D')->setWidth(10); // Talla
                $sheet->getColumnDimension('E')->setWidth(25); // Ubicación
                $sheet->getColumnDimension('F')->setWidth(15); // Cantidad
                $sheet->getColumnDimension('G')->setWidth(25); // Donante
                $sheet->getColumnDimension('H')->setWidth(15); // Estado
                $sheet->getColumnDimension('I')->setWidth(15); // Ingreso

                // 2. ALINEACIÓN VERTICAL (Para que se vea ordenado)
                $sheet->getStyle('A1:I'.$highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:I'.$highestRow)->getAlignment()->setWrapText(true); // Ajustar texto largo

                // 3. BUSCAR Y PROCESAR SEPARADORES
                for ($i = 1; $i <= $highestRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();

                    if ($cellValue === '[[SEPARADOR]]') {
                        $sheet->setCellValue('A' . $i, ''); // Borrar texto
                        $sheet->getRowDimension($i)->setRowHeight(40); // DAR ALTURA (ESPACIO)
                        
                        // Quitar bordes para que se vea blanco limpio
                        $sheet->getStyle("A$i:I$i")->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_NONE]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']]
                        ]);
                    }
                }
                
                // Congelar encabezado
                $sheet->freezePane('A6');
            },
        ];
    }
}