<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include '../../config/dbc.php';
include '../../libs/resizeimage.php';

page_protect();

$err = array();
$msg = array();
/* Set locale to Dutch */
setlocale(LC_ALL, 'th_TH');
$today = date("Y-m-d.H:i:s"); 

if($_POST['submit']=='Upload Image')
{
        $id = $_SESSION['patdesk'];
 //       createAvatar($_FILES['BackGround_file']['tmp_name']);
        
        if (is_dir(PT_IMAGE_PATH) && is_writable(PT_IMAGE_PATH)) {
	$target_dir = PT_IMAGE_PATH;
	$target_file = $target_dir ."PT_".$id."_".$today."-".basename($_FILES["fileToUpload"]["name"]);        
         
        include '../../libs/uploadimage.php';
        
         $sth = mysqli_query($link, "UPDATE users SET user_background = '$target_file' WHERE id = $id");
         $_SESSION['user_background'] = $target_file;
         
        } else {
            
            $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
            
        }
      

}


?>
<html>
<head>
<title>Upload image</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#myform").validate();
  });
  </script>
<link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
<?php

$url = $_GET['url'];
$maxWidth = $_GET['mwidth'];
$maxHeight = $_GET['mheight'];
$tmpExt = end(explode('/', $url));
$tmpExt = end(explode('/', $url));
$image = @file_get_contents($url);
if($image) {
    $im = new Imagick();
    $im->readImageBlob($image);
    $im->setImageFormat("png24");
    $geo=$im->getImageGeometry();
    //print_r($geo);
    $width=$geo['width'];
    $height=$geo['height'];
    if($width > $height)
    {
        $scale = ($width > $maxWidth) ? $maxWidth/$width : 1;
    }
    else
    {
        $scale = ($height > $maxHeight) ? $maxHeight/$height : 1;
    }
    $newWidth = $scale*$width;
    $newHeight = $scale*$height;
    $im->setImageCompressionQuality(85);
    $im->resizeImage($newWidth,$newHeight,Imagick::FILTER_LANCZOS,1.1);
    header("Content-type: image/png");
    echo $im;
    $im->clear();
    $im->destroy();
}

?>

<?php
/*
header('Content-type: image/jpeg');

$im = '../public/ptimages/*';
$image_files = glob($im);

foreach ($image_files as $image_file) {
    $image = new Imagick($image_file);
    // Do something for the image and so on...
    $image->thumbnailImage(1024,0);
}

$images->writeImages();
*/
?>

<form action="uploadpicture.php" method="post" name="myform" id="myform" enctype="multipart/form-data">
    <!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
    <input type="file" name="fileToUpload" id="fileToUpload">
    <span STYLE="Padding: 5px; border: 5px groove #ffffff"><input type="submit" value="Upload Image" name="submit"></span>
</form>
</body>
</html>
