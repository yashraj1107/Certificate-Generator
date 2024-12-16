      <?php
        require('vendor/autoload.php');
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
                    while (($line = fgetcsv($csvFile)) !== FALSE) {

                        // Get row data
                        $program_name =   $line[3];
                        $reg_no =  $line[4];
                        $name = $line[5];

                        if ($reg_no == "") {
                            break;
                        }
                        $i++;


                        $file_name = 'generated_pdfs/' . $name . ".pdf";

                        echo "<script>alert('" . $file_name . "')</script>";
                        $html = "";
                        $html .= '<html>

<head>
</head>

<body>
     <p style="text-align: center;"><img src="images/law.png"
               alt="logo" width="104" height="136" /></p>
     <p style="text-align: center;">&nbsp;</p>
     <p style="text-align: center;">COURSE COMPLETION CERTIFICATE</p>
     <p style="text-align: center;">This is to verify that&nbsp;</p>
     <p style="text-align: center;">Mr./Mrs. ' . $name . '</p>
     <p style="text-align: center;">Registration Number: ' . $reg_no . '</p>
     <p>has succesfully completed ' . $program_name . ' as per the IFIM Law School and karnataka State Law University norms during
          the academic year</p>
     <p>&nbsp;</p>
     <p>Date:</p>
     <p style="text-align: left;">Place:&nbsp;</p>
</body>

</html>';






                        $mpdf = new \Mpdf\Mpdf(['setAutoBottomMargin' => 'stretch', 'setAutoTopMargin' => 'stretch']);

                        // $mpdf->SetHTMLHeader($header);
                        $mpdf->defaultfooterline = 0;
                        $mpdf->setFooter(' 
<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000;  font-style: italic;"><tr>

<td width="25%"> &nbsp;</td>

<td width="50%" align="center" style=" font-style: italic;">IFIM LAW SCHOOL
<br>KIADB Industrial Area, 8 P & 9 P, Electronics City Phase 1, Bengaluru, 560100 (Karnataka).
 <br>
</td>

<td width="25%" style="text-align: right; ">&nbsp;</td>

</tr></table> 
');
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