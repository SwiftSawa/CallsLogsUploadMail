<?php
    function WriteSpreadSheet( $extensionNumber, $daysWork, $countIn, $timeIn, $avgDurationIn, $countCallPerDayIn, $avgDurationDayIn, $countOut, $timeOut, $avgDurationDayOut, $avgDurationOut, $countCallPerDayOut){
        require '../vendor/autoload.php';
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('C3:G3');
        $sheet->mergeCells('I3:M3');

        $sheet->setCellValue('A4', 'Extension Number');
        $sheet->setCellValue('A5', $extensionNumber);

        $sheet->setCellValue('B4', 'Days Worked');
        $sheet->setCellValue('B5', $daysWork);

        $sheet->setCellValue('C3', 'Incoming');
        $sheet->setCellValue('C4', 'Total Calls');
        $sheet->setCellValue('D4', 'Total Duration');
        $sheet->setCellValue('E4', 'Average Duration');
        $sheet->setCellValue('F4', 'Calls per day');
        $sheet->setCellValue('G4', 'Duration per day');
        $sheet->setCellValue('C5', $countIn);
        $sheet->setCellValue('D5', $timeIn);
        $sheet->setCellValue('E5', $avgDurationIn);
        $sheet->setCellValue('F5', $countCallPerDayIn);
        $sheet->setCellValue('G5', $avgDurationDayIn);

        $sheet->setCellValue('I3', 'Outgoing');
        $sheet->setCellValue('I4', 'Total Calls');
        $sheet->setCellValue('J4', 'Total Duration');
        $sheet->setCellValue('K4', 'Average Duration');
        $sheet->setCellValue('L4', 'Calls Per Day');
        $sheet->setCellValue('M4', 'Duration Per Day');
        $sheet->setCellValue('I5', $countOut);
        $sheet->setCellValue('J5', $timeOut);
        $sheet->setCellValue('K5', $avgDurationOut);
        $sheet->setCellValue('L5', $countCallPerDayOut);
        $sheet->setCellValue('M5', $avgDurationDayOut);

        $writer = new Xlsx($spreadsheet);
        $writer->save('testSS/out.xlsx');
    }

?>