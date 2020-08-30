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
    $geo = $im->getImageGeometry();
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
