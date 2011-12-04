<?php defined('DOCROOT') or die('No direct script access.');

class View_Extension_Thumbnail {
	// Shortcut to the thumbnail application config
	protected static $cfg;
	
	public static function get($file, $width = 0, $height = 0, $quality = 90, $full_path = true) {
		if (self::$cfg === null)
			self::$cfg = array(
                'default_width' => 120,
                'default_height' => 100,
                'cache_path' => DOCROOT.'media/images/cache/',
                'cache_url' => 'media/images/cache/'
            );
		$fileinfo = pathinfo($file);
		$new_width = max(0, (int) $width);
		$new_height = max(0, (int) $height);
		
		// set default width and height if neither are set already
		if ($new_width === 0 && $new_height === 0) {
			$new_width = self::$cfg['default_width'];
			$new_height = self::$cfg['default_height'];
		}
		
		$filename = $fileinfo['filename'].'_'.$new_width.'_'.$new_height.'_'.$quality.'.'.$fileinfo['extension'];
		$cache_dir = self::$cfg['cache_path'].self::_subpath($filename);
        $cache_url = self::$cfg['cache_url'].self::_subpath($filename);
		// Check the cached image
		if (file_exists($cache_dir.$filename)) {
			return $full_path
				? URL::base(true, true).self::$cfg['cache_url'].self::_subpath($filename).$filename
				: URL::base().$cache_url.$filename;
		}
		
		$filepath = DOCROOT.'media/images/'.$file;
		// original file is exist
		if (!file_exists($filepath)) {
			// nothing to resize
			// TODO maybe better return some 1pixel.gif?
			return null;
		}
		
		// Check the cache dir with subpath
		if (!file_exists($cache_dir)) {
			mkdir($cache_dir, 0775, true);
		}
		$photo = Image::factory($filepath);
		$photo->resize($new_width, $new_height, Image::INVERSE);
		$photo->crop($new_width, $new_height);
		$photo->save($cache_dir.$filename, $quality);
		
		return $full_path
				? URL::base(true, true).self::$cfg['cache_url'].self::_subpath($filename).$filename
				: URL::base().$cache_url.$filename;
	}
	
	protected static function _subpath($filename) {
		return $subpath = substr($filename, 0, 1). '/' . substr($filename, 1, 2) .'/';
	}
}