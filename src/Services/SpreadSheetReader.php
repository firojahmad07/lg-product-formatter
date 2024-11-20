<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class SpreadSheetReader
{
    public function loadNormalData(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $headers = [];
        foreach ($worksheet->getRowIterator(1, 1) as $headerRow) {
            foreach ($headerRow->getCellIterator() as $cell) {
                $headers[$cell->getColumn()] = $cell->getValue();
            }
        }

        $data = [];
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);

            foreach ($cellIterator as $cell) {
                $columnLetter = $cell->getColumn();
                if (isset($headers[$columnLetter])) {
                    $rowData[$headers[$columnLetter]] = $cell->getValue();
                }
            }
            $data[] = $rowData;
        }
        
        return $data;
    }

    public function loadProductData($filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $headers = [];
        $mainHeader = [];
        foreach ($worksheet->getRowIterator(1, 1) as $headerRow) {
            foreach ($headerRow->getCellIterator() as $cell) {
                $mainHeader[$cell->getColumn()] = $cell->getValue();
            }
        }
        
        foreach ($worksheet->getRowIterator(2, 2) as $headerRow) {
            $mainHeaderLabel = "";
            foreach ($headerRow->getCellIterator() as $cell) {
                $mainHeaderLabel = !empty($mainHeader[$cell->getColumn()]) ? $mainHeader[$cell->getColumn()] : $mainHeaderLabel;
                $value = preg_replace('/\s+/', ' ', $cell->getValue()); 
                $headers[$cell->getColumn()] = $value."-group-".$mainHeaderLabel;
            }
        }

        foreach ($worksheet->getRowIterator(3) as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);

            foreach ($cellIterator as $cell) {
                $columnLetter = $cell->getColumn();
                if (isset($headers[$columnLetter])) {
                    $rowData[$headers[$columnLetter]] = $cell->getValue();
                }
            }
            $data[] = $rowData;
        }

        return $data;
    }

    public function saveData(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
  
        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                // Calculate the cell address
                $cell = Coordinate::stringFromColumnIndex($colIndex + 1) . ($rowIndex + 1);
                $sheet->setCellValue($cell, $value);
            }
        }
  
          // Write the spreadsheet to a file
          $writer = new Xlsx($spreadsheet);
          $fileName = './public/export.xlsx';
  
          // Create a temporary file in memory
        //   $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        //   dump($tempFile);die;
          $writer->save($fileName);
    }
}