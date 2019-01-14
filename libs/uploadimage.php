<?php
//$target_dir = IMAGE_PATH;
//$target_file = $target_dir ."bg_".$id."-".basename($_FILES["fileToUpload"]["name"]);
//$target_file = $target_file_path;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $msg[] ="File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $msg[] ="File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    unlink($target_file);
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    $err[] ="Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $err[] ="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an $error
if ($uploadOk == 0) {
    $err[] ="Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $msg[] ="The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        $err[] ="Sorry, there was an error uploading your file.";
    }
}
?> 
