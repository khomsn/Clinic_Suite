<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include '../login/dbc.php';
include '../libs/resizeimage.php';

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
         
        include '../libs/uploadimage.php';
        
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
<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>

<?php
header('Content-type: image/jpeg');

$im = PT_IMAGE_PATH."*";
$image_files = glob($im);

foreach ($image_files as $image_file) {
    $image = new Imagick($image_file);
    // Do something for the image and so on...
    $image->thumbnailImage(1024,0);
}

$images->writeImages();

?>

<form action="uploadpicture.php" method="post" name="myform" id="myform" enctype="multipart/form-data">
    <!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
    <input type="file" name="fileToUpload" id="fileToUpload">
    <span STYLE="Padding: 5px; border: 5px groove #ffffff"><input type="submit" value="Upload Image" name="submit"></span>
</form>

<?php
/* Read the image */
$im = new Imagick("test.png");

/* Thumbnail the image */
$im->thumbnailImage(200, null);

/* Create a border for the image */
$im->borderImage(new ImagickPixel("white"), 5, 5);

/* Clone the image and flip it */
$reflection = $im->clone();
$reflection->flipImage();

/* Create gradient. It will be overlayed on the reflection */
$gradient = new Imagick();

/* Gradient needs to be large enough for the image and the borders */
$gradient->newPseudoImage($reflection->getImageWidth() + 10, $reflection->getImageHeight() + 10, "gradient:transparent-black");

/* Composite the gradient on the reflection */
$reflection->compositeImage($gradient, imagick::COMPOSITE_OVER, 0, 0);

/* Add some opacity. Requires ImageMagick 6.2.9 or later */
$reflection->setImageOpacity( 0.3 );

/* Create an empty canvas */
$canvas = new Imagick();

/* Canvas needs to be large enough to hold the both images */
$width = $im->getImageWidth() + 40;
$height = ($im->getImageHeight() * 2) + 30;
$canvas->newImage($width, $height, new ImagickPixel("black"));
$canvas->setImageFormat("png");

/* Composite the original image and the reflection on the canvas */
$canvas->compositeImage($im, imagick::COMPOSITE_OVER, 20, 10);
$canvas->compositeImage($reflection, imagick::COMPOSITE_OVER, 20, $im->getImageHeight() + 10);

/* Output the image*/
header("Content-Type: image/png");
echo $canvas;
?>

</body>
</html>
