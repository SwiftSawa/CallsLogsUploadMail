<?php
    require 'vendor/autoload.php';
    require 'src/config.php';
    require 'src/AddTime.php';
    require 'src/DivTime.php';
    require 'src/DifferenceTime.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    if(isset($_POST['upload'])){

        $newSpaceXL=4;

        $dateInitial= $_POST['dateInitial'];
        $dateFinal= $_POST['dateFinal'];
        
        if ($dateInitial <= $dateFinal){
            $sqlGetExtensionNum= "SELECT DISTINCT ExtensionNum FROM testtable_v2";
            $getExtensionNum=$conn->query($sqlGetExtensionNum);
            $extensionNums=$getExtensionNum->fetch_all(MYSQLI_ASSOC);
            // print_r($extensionNums);
            // echo "<br>";
            $daysWork=DifferenceTime($dateInitial, $dateFinal);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            

            $sheet->setCellValue('A1', 'Period From');
            $sheet->setCellValue('B1', $dateInitial);
            $sheet->setCellValue('C1', "To");
            $sheet->setCellValue('D1', $dateFinal);
            $sheet->setCellValue('D3', $dateFinal);

            $sheet->mergeCells("C2:G2");
            $sheet->mergeCells("I2:M2");
            $sheet->setCellValue('C2', 'Incoming');
            $sheet->setCellValue('I2', 'Outgoing');

            $sheet->setCellValue('A3', 'Ext');
            $sheet->setCellValue('B3', 'Days Worked');

            $sheet->setCellValue('C3', 'Total Calls');
            $sheet->setCellValue('D3', 'Total Duraton');
            $sheet->setCellValue('E3', 'Average Duration');
            $sheet->setCellValue('F3', 'Calls Per day');
            $sheet->setCellValue('G3', 'Duration per day');

            $sheet->setCellValue('I3', 'Total Calls');
            $sheet->setCellValue('J3', 'Total Duraton');
            $sheet->setCellValue('K3', 'Average Duration');
            $sheet->setCellValue('L3', 'Calls Per day');
            $sheet->setCellValue('M3', 'Duration per day');


            foreach($extensionNums as $extensionNum){
                $timeOut="00:00:00";
                $timeIn="00:00:00";
                $countOut=0;
                $countIn=0;

                // echo $extensionNum['ExtensionNum'];
                $extNum = $extensionNum['ExtensionNum'];
                // echo "<br>";
                $sqlGetDataDayWise="SELECT * FROM testtable_v2 WHERE (dateT BETWEEN '$dateInitial 00:00:00' AND '$dateFinal 23:59:59') AND ExtensionNum = '$extNum'";
                $getDataDayWise=$conn->query($sqlGetDataDayWise);
                $dataDayWise=$getDataDayWise->fetch_all(MYSQLI_ASSOC);
                foreach($dataDayWise as $num=>$data){
    
                    if($data['type']=="Incoming"){
                        $countIn++;
                        $timeIn =AddTime($timeIn, $data['duration']);
                    }else{
                        $countOut++;
                        $timeOut= AddTime($timeOut, $data['duration']);
                    }
                }

                $countCallPerDayIn= round($countIn/$daysWork);
                $countCallPerDayOut= round($countOut/$daysWork);
                if($countIn!= 0){
                    $avgDurationIn=  DivTime($timeIn, $countIn);
                    $avgDurationOut= DivTime($timeOut, $countOut);
                }else{}
    
                $avgDurationDayIn=  DivTime($timeIn, $daysWork);
                $avgDurationDayOut= DivTime($timeOut, $daysWork);

                
                $sheet->setCellValue("A$newSpaceXL", $extNum);
                $sheet->setCellValue("B$newSpaceXL", $daysWork);
                
                $sheet->setCellValue("C$newSpaceXL", $countIn);
                $sheet->setCellValue("D$newSpaceXL", $timeIn);
                $sheet->setCellValue("E$newSpaceXL", $avgDurationIn);
                $sheet->setCellValue("F$newSpaceXL", $countCallPerDayIn);
                $sheet->setCellValue("G$newSpaceXL", $avgDurationDayIn);

                $sheet->setCellValue("I$newSpaceXL", $countOut);
                $sheet->setCellValue("J$newSpaceXL", $timeOut);
                $sheet->setCellValue("K$newSpaceXL", $avgDurationOut);
                $sheet->setCellValue("L$newSpaceXL", $countCallPerDayOut);
                $sheet->setCellValue("M$newSpaceXL", $avgDurationDayOut);

                $newSpaceXL++;

                $writer = new Xlsx($spreadsheet);
                $writer->save('testSS/report.xlsx');
    
                // echo "Number of days worked: ";
                // echo $daysWork; 
                // echo "<br>";

                // echo "Incoming call Number: ";
                // echo $countIn; 
                // echo "<br>";
                // echo "Outgoing call number: ";
                // echo $countOut; 
                // echo "<br>";
            
                // echo "Incoming call time: ";
                // echo $timeIn; 
                // echo "<br>";
                // echo "Outgoing call time: ";
                // echo $timeOut; 
                // echo "<br>";

                // echo "Incoming call Number per day: ";
                // echo $countCallPerDayIn; 
                // echo "<br>";
                // echo "Outgoing call number per day: ";
                // echo $countCallPerDayOut; 
                // echo "<br>";
                // if($countIn!= 0){
                //     echo "Average Duration Incoming Calls: ";
                //     echo $avgDurationIn; 
                //     echo "<br>";
                //     echo "Average Duration Outgoing Calls: ";
                //     echo $avgDurationOut; 
                //     echo "<br>";
                // }else{}
                

                // echo "Average Duration Incoming Calls per day: ";
                // echo $avgDurationDayIn; 
                // echo "<br>";
                // echo "Average Duration Outgoing Calls per day: ";
                // echo $avgDurationDayOut; 
                // echo "<br>";
                // echo "<br>";
                // echo "<br>";
                // echo "<br>";
            }
        }else{
            echo "Enter corrct dates";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Report</title>
<link rel="stylesheet" href="https://bootswatch.com/4/cosmo/bootstrap.min.css" />
</head>
<body>
<?php include "src/nav.php" ?>

<div class="container" style="padding-top: 150px;">
        <div class="row justify-content-center align-items-center">
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <lable>From</lable>
                    <input type="date" name="dateInitial" class="form-control" required>
                </div>
                <div class="form-group">
                    <lable>To</lable>
                    <input type="date" name="dateFinal" class="form-control" required>
                </div>
                <div >
                <button type="submit" name="upload" class="btn btn-primary">Mail</button>
                </div>
            </form>
        </div>
    </div>  


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>