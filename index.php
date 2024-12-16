<?php
        require('vendor/autoload.php');
        use Endroid\QrCode\QrCode;
        use Endroid\QrCode\Writer\PngWriter;
        use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

        ini_set('max_execution_time', 0);
        session_start();

        // include('config.php');
        error_reporting(0);

        if (isset($_POST['import'])) {

            // Allowed mime types
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

            // Validate whether selected file is a CSV file
            if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {

                // If the file is uploaded
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                    // Open uploaded CSV file with read-only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                    // Skip the first line
                    fgetcsv($csvFile);

                    // Parse data from CSV file line by line

                    $i = 1;
                    $pass = 0;
                    $fail = 0;
                    // $fontDir = __DIR__ . 'C:\xampp\htdocs\pdf_generate\vendor\mpdf\mpdf\ttfonts'; // Ensure you upload the Pacifico font here
                    // $fontData = [
                    //     'pacifico' => [
                    //         'R' => 'Pacifico-Regular.ttf', // Regular font file in the fonts directory
                    //     ]
                    // ];
                    while (($line = fgetcsv($csvFile)) !== FALSE) {

                        // Get row data
                        $name = $line[0];
                        $unique_identifier  =  $line[1];
                        $number  =  $line[2];

                        if ($unique_identifier == "") {
                            break;
                        }
                        $i++;
                        
                        $baseURL = 'https://kos.vijaybhoomi.edu.in/ValidateDegreeCertificate?unique_identifier=';
                        $link = $baseURL . $unique_identifier;
                        
// Generate QR Code
$qrCode = new QrCode($link);
$writer = new PngWriter();
$sanitizedName = str_replace(' ', '_', $name); // Replace spaces with underscores
$qrPath = 'qrcodes/' . $sanitizedName . '.png';
$result = $writer->write($qrCode);
$result->saveToFile($qrPath);
                        $file_name = 'generated_pdfs/' . $name . ".pdf";

                        echo "<script>alert('" . $file_name . "')</script>";
                        $html = "";
                        $html .= '
                        <html>

<head>

</head>

<body style="margin: 0; font-family: Arial, sans-serif;">
<div style="width: 100%; height: 100%; padding: 3mm; box-sizing: border-box; border: 8px solid grey; margin: auto; max-width: 150mm; max-height: 250mm;">
<div style="margin-top: 1mm;width: 100%; height: 90%; margin-bottom: 2mm; box-sizing: border-box; border: 4px solid orange; padding: 10px;">
<!-- Top Header Section -->
<table style="width: 100%; border-collapse: collapse; margin-bottom: 3mm;">
<tr>
<!-- QR Code -->
<td style="width: 15%; text-align: left; vertical-align: top;">
<img src="'.$qrPath.'" alt="QR Code" style="width: 90px; height: 90px;" />
</td>

<!-- Logo -->
<td style="width: 70%; text-align: center;">
<img src="images/jagsom.png" alt="logo" style="max-width: 300px; height: auto;" />
</td>

<!-- Certificate Number -->
<td style="width: 15%; text-align: right; vertical-align: top; font-size: 13px; font-weight: bold; color: red;">
PGCIA/ELE/MILE/2024/AUG/'.$number.'
</td>
</tr>
</table>
<br>


<!-- Certificate Content -->
<div style="text-align: center;">
<p style="font-size: 14px; margin: 0;">This is to certify that</p><br>

<p style="font-family: Pacifico !important; font-size: 37px; margin: 3mm 0;"><i><b>'.$name.'</b></i></p><br>

<p style="font-size: 15px; margin: 0;">
Has successfully completed the 3-Month Term of
<br>
<strong>Post Graduate Certificate in International Accounting and Analytics</strong>
<br>
conducted by the Jagdish Sheth School of Management from
<strong>March, 2024 to July, 2024</strong>.
</p>
</div>

<br>
<br>

<table style="width: 100%; border-collapse: collapse; text-align: center;">
<tr>
<!-- Program Chair -->
<td style="font-size: 18px; vertical-align: middle; padding: 10px;">Assoc. Dean</td>

<!-- Seal -->
<td style="text-align: center; vertical-align: middle; padding: 10px;">
<img src="images/seal.png" alt="seal" style="display: block; margin: auto; width: 220px; height: 220px;" />
</td>

<!-- Joint Director -->
<td style="font-size: 18px; vertical-align: middle; padding: 10px;">Joint Director</td>
</tr>
</table>
</div>
</div>
</body>

</html>


';

$mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'orientation' => 'L']); // 'L' stands for Landscape

                        $mpdf->WriteHTML($html);
                        // $mpdf->WriteHTML($html);
                        ob_clean();
                        $mpdf->Output($file_name, 'F');


                        $pass++;
                    }

                    echo "<script> alert('$pass  pdfs Generated Successfully! ($fail failed.)');</script>";
                    echo "<script>window.location.href='generated_pdfs/';</script>";


                    //generation ends

                }


                $msg = 'SUCCESSFULLY IMPORTED...';
                echo "<script>alert('" . $msg . "')</script>";
                // Close opened CSV file
                fclose($csvFile);
            } else {
                $error = 'SOMETHING GONE WRONG PLEASE TRY AGAIN...';
                echo "<script>alert('" . $error . "')</script>";
            }
        }

        ?>

      <!DOCTYPE html>
      <html lang="en">

      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <!-- Meta, title, CSS, favicons, etc. -->
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link href="dist/images/fav.png" rel="shortcut icon">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <title>Upload excel</title>


          <style>
              .site_title {
                  overflow: inherit;
              }

              .nav_title {
                  height: 198px;
                  margin-top: -59px;
              }

              .required {
                  color: red;
              }
          </style>
      </head>

      <body class="nav-md" style="overflow:hidden">

          <!-- page content -->
          <div class="right_col" role="main">

              <div class="page-title">
                  <div class="title_left">
                      <h4>Upload .CSV excel file only </h4>
                  </div>
              </div>

              <div class="clearfix"></div>
              <div class="row">
                  <div class="col-md-12 col-sm-12 ">
                      <div class="x_panel">
                          <div class="x_title">
                              <h2>(FIRST ROW OF THE EXCEL .CSV FILE WILL BE NEGLECTED..)</h2>


                          </div>
                          <br> <br> <br>
                          <div class="item form-group">
                              <div class="col-md-6 col-sm-6 offset-md-3">

                                  <form action="" name="upload_excel" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                      <label>Select .CSV Excel File :</label>
                                      <input type="file" name="file" id="file" accept=".csv,.txt" onchange="ValidateSingleInput(this);" required=" " />
                                      <br />
                              </div>
                          </div> <br /> <br /> <br />
                          <div class="col-md-6 col-sm-6 offset-md-9">
                              <input type="submit" name="import" class="btn btn-success" data-loading-text="Loading..." value="I M P O R T" />
                          </div>
                          </form>

                          <br>
                          <?php echo $log_msg; ?> <br> <br><?php echo $error; ?> <br> <?php echo $qstring; ?> <br>
                      </div>
                  </div>
              </div>
          </div>
          </div>


      </body>

      </html>