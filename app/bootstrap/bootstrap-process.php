<html>
<head>
<link rel="stylesheet" href="../login page/style.css">
</head>

<body>
<!-- side nav bar vertical -->
<ul class="ul">
       <p padding='5px'>  <b> WELCOME Administrator</b> </p>
  <li><a class="active" href="bootstrap.php">Home</a></li>
  <li><a href="../login page/logout.php">Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style="margin-left:18%;padding:16px 16px;height:1000px; width:710px; background-color:#f9f9f9">


</html>



<?php
# edit the file included below. the bootstrap logic is there
require_once 'include/bootstrap.php';
require_once 'include/common.php';
$dao = new Round_noDAO();


if (isset($_POST['submit'])){


   
    $result = doBootstrap();
    $round_no = 1;

    $dao->add_round($round_no);
    $status = $result['status'];
    echo "<html>
    <center>
        <table border = '1' >
            <tr>
                <th>Status</th>
                <td colspan='2'>$status</td>
            </tr>";
    
    if (array_key_exists('error', $result) && in_array("input files not found", $result['error'])){
        echo "<tr>
                <td>Error</td>
                <td colspan='2'>input files not found</td>
            </tr>";
    }else{
        echo  "<tr>
                <th colspan ='3' style='center-aligned'>Number of lines loaded</td>
            </tr>";
            $num_loaded = $result['num-record-loaded'];
            for ($i = 0; $i<count($num_loaded); $i++){
                foreach($num_loaded[$i] as $file=>$num_line){
                    echo "<tr>
                            <td>$file</td>
                            <td colspan='2'>$num_line</td>
                        </tr>";
                }
            }
            if (array_key_exists('error', $result)){
                echo "<tr>
                        <th colspan='3'style='center-aligned'>Errors</td>
                    </tr>";
                    echo "<tr>
                            <td>File</td>
                            <td>Line</td>
                            <td>Issue</td>
                        </tr>";
                $errors = $result['error'];
                for ($i=0; $i<count($errors); $i++){
        
                    $messages = $errors[$i]['message'];
                    
                    if (count($messages) > 1){
                        $messages = implode(',', $messages);
                    }else{
                        $messages = $messages[0];
                    }
                    
                    $file = $errors[$i]['file'];
                    $line = $errors[$i]['line'];
                    echo "<tr>
                            <td>$file</td>
                            <td>$line</td>
                            <td>$messages</td>
                        </tr>";
                }
            }

    }
    
        
    echo "</table></center></html>";
 echo "  
    <center>
    <br><br>
            <a href='bootstrap.php'>Back</a>
            </center> ";

}  
?>
<!-- <html>
    <a href = 'bootstrap.php'>Back</a>
</html> -->


