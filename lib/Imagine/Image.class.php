<?php

class Image {

	static function resize($image, $width, $height, $mode) {
		$info = pathinfo($image);
		$dest = $info['dirname'].'/'.$info['filename']."_$width"."x$height".'.'.$info['extension'];
		if(file_exists($dest)) {
			return $dest;
		}
		require_once('imagine.phar');

		$imagine = new Imagine\Gd\Imagine();
		$size = new Imagine\Image\Box($width, $height);

		$imagine->open($image)->thumbnail($size, $mode)->save($dest);

		return $dest;
	}

}