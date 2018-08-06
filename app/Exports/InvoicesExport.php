<?php
/**
 * Created by PhpStorm.
 * User: shapran
 * Date: 17.07.18
 * Time: 18:01
 */

namespace Grafit\Exports;

use Grafit\Invoice;
use DB;

class InvoicesExport
{
    private $templateName = "InvoicesExport.xlsx";

    private $spreadsheet;

    private $data = [];

    public function query()
    {
        $this->data['rows'] = Invoice::query()
            ->leftJoin('spr_kontrs', 'spr_kontrs.id', '=', 'doc_invoices.id_kontr')
            ->leftJoin('doc_invoices_tab', 'doc_invoices_tab.id_doc', '=', 'doc_invoices.id')
            ->select([
                'doc_invoices.date_doc',
                'doc_invoices.num_doc',
                'spr_kontrs.name',
                DB::raw('sum(doc_invoices_tab.sum) as sum')

            ])
            ->groupBy([
                'doc_invoices.date_doc',
                'doc_invoices.num_doc',
                'spr_kontrs.name',
            ])
            ->get();

        return $this;

    }

    public function fill()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $this->spreadsheet = $reader->load(resource_path('exports/templates/'.$this->templateName));

        // sheet 1
        $sheet = $this->spreadsheet->getSheet(0);

        $num = 0;
        foreach($this->data['rows'] as $row)
        {
            $num++;
            if ($num > 1)
            {
                for($i=1; $i<=5; $i++){
                    $sheet->duplicateStyle($sheet->getStyleByColumnAndRow($i, 4), $sheet->getCellByColumnAndRow($i, 3+$num)->getCoordinate());
                }
            }

            $sheet->setCellValueByColumnAndRow(1, 3+$num, $num );
            $sheet->setCellValueByColumnAndRow(2, 3+$num, $row['num_doc']);
            $sheet->setCellValueByColumnAndRow(3, 3+$num, $row['date_doc']->format('d.m.Y'));
            $sheet->setCellValueByColumnAndRow(4, 3+$num, $row['name']);
            $sheet->setCellValueByColumnAndRow(5, 3+$num, $row['sum']);
        }

        $sheet->setSelectedCells("A1");
        return $this;
    }

    public function download($filename)
    {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
        $writer->save(storage_path($filename));

        return response()->download(storage_path($filename))->deleteFileAfterSend();
    }

}