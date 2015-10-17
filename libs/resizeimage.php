<?php
    /**
     * Resize Image
     *
     * Takes the source image and resizes it to the specified width & height or proportionally if crop is off.
     * @access public
     * @author Jay Zawrotny <jayzawrotny@gmail.com>
     * @license Do whatever you want with it.
     * @param string $source_image The location to the original raw image.
     * @param string $destination_filename The location to save the new image.
     * @param int $width The desired width of the new image
     * @param int $height The desired height of the new image.
     * @param int $quality The quality of the JPG to produce 1 - 100
     * @param bool $crop Whether to crop the image or not. It always crops from the center.
     */
    function resize_image($source_image, $destination_filename, $width = 44, $height = 44, $quality = 85, $crop = true) {

	if ( ! $image_data = getimagesize( $source_image ) ) {
		return false;
	}

	switch( $image_data['mime'] ) {
		case 'image/gif':
			$get_func = 'imagecreatefromgif';
			$suffix = ".gif";
		break;
		case 'image/jpeg';
			$get_func = 'imagecreatefromjpeg';
			$suffix = ".jpg";
		break;
		case 'image/png':
			$get_func = 'imagecreatefrompng';
			$suffix = ".png";
		break;
	}

	$img_original = call_user_func( $get_func, $source_image );
	$old_width = $image_data[0];
	$old_height = $image_data[1];
	$new_width = $width;
	$new_height = $height;
	$src_x = 0;
	$src_y = 0;
	$current_ratio = round( $old_width / $old_height, 2 );
	$desired_ratio_after = round( $width / $height, 2 );
	$desired_ratio_before = round( $height / $width, 2 );

	if ( $old_width < $width || $old_height < $height ) {

		 // The desired image size is bigger than the original image. 
		 // Best not to do anything at all really.
		return false;
	}

	// If the crop option is left on, it will take an image and best fit it
	// so it will always come out the exact specified size.
	if ( $crop ) {
            
		// create empty image of the specified size
		$new_image = imagecreatetruecolor( $width, $height );

		// Landscape Image
		if( $current_ratio > $desired_ratio_after ) {
			$new_width = $old_width * $height / $old_height;
		}

		// Nearly square ratio image.
		if ( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after ) {
                    
                    if ( $old_width > $old_height ) {                        
                        $new_height = max( $width, $height );
                        $new_width = $old_width * $new_height / $old_height;
                    } else {
                        $new_height = $old_height * $width / $old_width;
                    }
		}

		// Portrait sized image
		if ( $current_ratio < $desired_ratio_before  ) {
                    $new_height = $old_height * $width / $old_width;
		}

		// Find out the ratio of the original photo to it's new, thumbnail-based size
		// for both the width and the height. It's used to find out where to crop.
		$width_ratio = $old_width / $new_width;
		$height_ratio = $old_height / $new_height;

		// Calculate where to crop based on the center of the image
		$src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
		$src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
	}
	// Don't crop the image, just resize it proportionally
	else {
            
		if ( $old_width > $old_height ) {
                    $ratio = max( $old_width, $old_height ) / max( $width, $height );
		} else {
                    $ratio = max( $old_width, $old_height ) / min( $width, $height );
		}

		$new_width = $old_width / $ratio;
		$new_height = $old_height / $ratio;

		$new_image = imagecreatetruecolor( $new_width, $new_height );
	}

	// Where all the real magic happens
	imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );

	// Save it as a JPG File with our $destination_filename param.
	imagejpeg( $new_image, $destination_filename, $quality  );

	// Destroy the evidence!
	imagedestroy( $new_image );
	imagedestroy( $img_original );

	// Return true because it worked and we're happy. Let the dancing commence!
	return true;
    }
    

?>