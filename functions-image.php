<?php

/**
 * scale image by the same scale
 * @author newnius
 * @param string $src_img
 * @param int $dst_max_width=100
 * @param int $dst_max_height=80
 * @param string $prefix='s_'
 * @return string $dst_img or null for wrong type
 *
 */
function image_scale($src_img, $dst_max_width=500, $dst_max_height=400, $prefix='s_'){
	if(file_exists($src_img)){
		$src_img_info=getimagesize($src_img);
		$src_width=$src_img_info[0];
		$src_height=$src_img_info[1];

		$dst_img_addr=pathinfo($src_img,PATHINFO_DIRNAME).'/'.$prefix.pathinfo($src_img, PATHINFO_BASENAME);

		$scale=1;
		if(($dst_max_width / $src_width) < ($dst_max_height / $src_height)){
			$scale=$dst_max_width / $src_width;
		}else{
			$scale=$dst_max_height / $src_height;
		}
		$dst_width=floor($src_width * $scale);
		$dst_height=floor($src_height * $scale);

		/*1 = GIF
		 *2 = JPG
		 *3 = PNG
		 *4 = SWF
		 *5 = PSD
		 *6 = BMP
		 *7...
		 */
		$src_img_im = null;
		$dst_img = imagecreatetruecolor($dst_max_width, $dst_max_height);


		/*
		 *
		 */
		$dst_bg_color=imagecolorallocate($dst_img,254,254,254);
		imagefill($dst_img,0,0,$dst_bg_color);

		switch($src_img_info[2]){
			case 1://gif
				$src_img_im = imagecreatefromgif($src_img);
				imagecopyresampled($dst_img, $src_img_im, ($dst_max_width-$dst_width)/2, ($dst_max_height-$dst_height)/2, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				imagecolortransparent($dst_img, $dst_bg_color);
				imagegif($dst_img, $dst_img_addr);
				break;

			case 2://jpg
				$src_img_im = imagecreatefromjpeg($src_img);
				imagecopyresampled($dst_img, $src_img_im, ($dst_max_width-$dst_width)/2, ($dst_max_height-$dst_height)/2, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				imagecolortransparent($dst_img, $dst_bg_color);
				imagejpeg($dst_img, $dst_img_addr);
				break;

			case 3://png
				$src_img_im = imagecreatefrompng($src_img);
				imagecopyresampled($dst_img, $src_img_im, ($dst_max_width-$dst_width)/2, ($dst_max_height-$dst_height)/2, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				imagecolortransparent($dst_img, $dst_bg_color);
				imagepng($dst_img, $dst_img_addr);
				break;

			default:
				return null;
				break;
		}

		//free memory
		if($src_img_im!=null){
			imagedestroy($src_img_im);
		}
		if($dst_img!=null){
			imagedestroy($dst_img);
		}

	}else{
		return null;
	}
	return $dst_img_addr;
}

?>
