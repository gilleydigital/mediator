<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Styles extends Media{
	const EXT = 'css';

	const CSS_PATH = 'styles/css/';

	const BASE = 0; // Reset, normalize, etc.
	
	const INCLUDED = 10; // Forms, colors

	const PLUGIN = 20; // CSS for JS plugins

	const TEMPLATE = 30; // Global stylsheet
	
	const CUTOFF = 30; // The cutoff for what goes in the combined production stylesheet

	const PAGE = 40; // 1-page tweaks
	
	public static function add ($name, $priority = Styles::PAGE, $type = 'styles')
	{
		parent::add($name, $priority, $type);
	}
	
	public static function output ($profile = 'default')
	{
		$sheets = Media::get_assets($profile, 'styles');
		
		$prod_file_exists = false;
		$uncompressed = '';
		
		foreach ($sheets AS $file)
		{
			if ($file['priority'] <= Styles::CUTOFF)
			{
				$prod_file_exists = true;
			}
			
			// Dev: output everything, Prod: only if more specific than template (otherwise it's in the combined prod stylesheet)
			if ((Kohana::$environment !== Kohana::PRODUCTION) or ($file['priority'] > Styles::CUTOFF))
			{
				$path = Media::MEDIA_FOLDER.'/'.Styles::get_path($file).'.'.Styles::EXT;
				$uncompressed .= HTML::style($path);
				$uncompressed .= PHP_EOL;
			}
		}
		
		// if production
		if ((Kohana::$environment === Kohana::PRODUCTION) and ($prod_file_exists === true))
		{
			$prod = APPPATH.'../assets/'.$profile.'.'.Styles::EXT;
			if ($contents = file_get_contents($prod))
			{
				$hash = md5($contents);
				echo HTML::style($profile.'-fp-'.$hash.'.'.Styles::EXT);
			}
		}
		
		echo $uncompressed;
	}
		
	public static function get_path ($file)
	{
		$path = $file['prefix'].Styles::CSS_PATH.$file['name'];
		return $path;
	}
}
