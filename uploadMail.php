<?php
    require 'vendor/autoload.php';
    require 'src/config.php';
    
    require 'src/AddTime.php';
    require 'src/DivTime.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
   
    if(isset($_POST['upload'])){

        $extensionNumber=$_POST['extensionNumber'];
        $daysWork=$_POST['daysWorked'];

        $file_name= $_FILES['file']['name'];
        $file_tempLoc= $_FILES['file']['tmp_name'];

        $file_newLoc= "upload/".$file_name;
        $file_parts = pathinfo($file_name);

        $timeOut="00:00:00";
        $timeIn="00:00:00";
        $countOut=0;
        $countIn=0;
        if ($file_parts['extension'] == 'csv'){

            move_uploaded_file($file_tempLoc, $file_newLoc);       
            $inputFileType = 'Csv';
            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file_newLoc);
            $table = $spreadsheet->getActiveSheet()->toArray('%%', true, true, true);

            for($row=1; $row<= count($table); $row++) {
                if($table[$row]['A']=='Type'){

                }else{
                    $date=date_create($table[$row]['C']);                   // geting date and time and conver from dd/MM/YYYY HH:MM
                    $table[$row]['C'] = date_format($date, "Y-d-m H:i:s");  // to YYYY-DD-MM HH:MM:MS

                    if($table[$row]['A']=="Incoming"){
                        $countIn++;
                        $timeIn =AddTime($timeIn, $table[$row]['D']);
                    }else{
                        $countOut++;
                        $timeOut= AddTime($timeOut, $table[$row]['D']);
                    }
                    
                    // $data = "'" . implode("','", $table[$row]) . "'";
                    // $enterData="INSERT INTO testtable_v1 ( type, Site, dateT, duration, CallNum , DestiNum , Outcome) VALUES ( $data );";
                    // if($conn->query($enterData)){}else{
                    //     echo "Error $conn->error";
                    // }
                }
            }
        }else{
            echo "Wrong File";
        }

        $countCallPerDayIn= round($countIn/$daysWork);
        $countCallPerDayOut= round($countOut/$daysWork);

        $avgDurationIn=  DivTime($timeIn, $countIn);
        $avgDurationOut= DivTime($timeOut, $countOut);

        $avgDurationDayIn=  DivTime($timeIn, $daysWork);
        $avgDurationDayOut= DivTime($timeOut, $daysWork);

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

        echo "Extension Number: ";
        echo $extensionNumber; 
        echo "<br>";
        echo "Number of days worked: ";
        echo $daysWork; 
        echo "<br>";

        echo "Incoming call Number: ";
        echo $countIn; 
        echo "<br>";
        echo "Outgoing call number: ";
        echo $countOut; 
        echo "<br>";
       
        echo "Incoming call time: ";
        echo $timeIn; 
        echo "<br>";
        echo "Outgoing call time: ";
        echo $timeOut; 
        echo "<br>";

        echo "Incoming call Number per day: ";
        echo $countCallPerDayIn; 
        echo "<br>";
        echo "Outgoing call number per day: ";
        echo $countCallPerDayOut; 
        echo "<br>";

        echo "Average Duration Incoming Calls: ";
        echo $avgDurationIn; 
        echo "<br>";
        echo "Average Duration Outgoing Calls: ";
        echo $avgDurationOut; 
        echo "<br>";

        echo "Average Duration Incoming Calls per day: ";
        echo $avgDurationDayIn; 
        echo "<br>";
        echo "Average Duration Outgoing Calls per day: ";
        echo $avgDurationDayOut; 
        echo "<br>";

    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload and Mail</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/cosmo/bootstrap.min.css" />
</head>
<body>
   <?php include "src/nav.php" ?>

    <div class="container" style="padding-top: 150px;">
        <div class="row justify-content-center align-items-center">
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <lable>Extension Number</lable>
                    <input type="number" name="extensionNumber" class="form-control" required>
                </div>
                <div class="form-group">
                    <lable>Number Days Worked</lable>
                    <input type="number" name="daysWorked" class="form-control" required>
                </div>
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="customFile" required>
                    <label class="custom-file-label" for="customFile">Upload Call files Here</label>
                </div>
                <div style="padding-top: 20px;">
                <button type="submit" name="upload" class="btn btn-primary">Upload And Mail</button>
                </div>
            </form>
        </div>
    </div>  


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>