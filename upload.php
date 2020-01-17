<?php

require 'vendor/autoload.php';
require 'src/config.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST['upload'])){

    $extensionNumber=$_POST['extensionNumber'];

    $file_name= $_FILES['file']['name'];
    $file_tempLoc= $_FILES['file']['tmp_name'];

    $file_newLoc= "upload/".$file_name;
    $file_parts = pathinfo($file_name);

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
                
                 $data = "'" . implode("','", $table[$row]) ."','$extensionNumber'";
                 $enterData="INSERT INTO testtable_v2 ( type, Site, dateT, duration, CallNum , DestiNum , Outcome, ExtensionNum) VALUES ( $data );";
                //  echo $enterData;
                //  echo "<br>";
                if($conn->query($enterData)){}else{
                    echo "Error $conn->error";
                }
            }
        }
    }else{
        echo "Wrong File";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload</title>
<link rel="stylesheet" href="https://bootswatch.com/4/cosmo/bootstrap.min.css" />
</head>
<body>

<?php include "src/nav.php" ?>

    <div class="container" style="padding-top: 200px;">
        <div class="row justify-content-center align-items-center">
            <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <lable>Extension Number</lable>
                    <input type="number" name="extensionNumber" class="form-control" required>
                </div>
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="customFile" required>
                    <label class="custom-file-label" for="customFile">Upload Call files Here</label>
                </div>
                <div style="padding-top: 15px;">
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>  


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>