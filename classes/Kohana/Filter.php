<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Filter{
	public static function minify ($input, $type)
	{
		if ($type === 'styles')
		{
			return Filter::minify_styles($input);
		}

		if ($type === 'scripts')
		{
			return Filter::minify_scripts($input);
		}
	}
	
	private static function minify_styles ($input)
	{
		require_once Kohana::find_file('vendor', 'cssmin/CssMin');
		
		$output = CssMin::minify($input);
		
		return $output;
	}
	
	private static function minify_scripts ($input)
	{
		require_once Kohana::find_file('vendor', 'jsmin/jsmin');
		
		$output = JsMin::minify($input);
		
		return $output;
	}
	
	// For overriding with custom filters
	public static function custom ($input)
	{
		return $input;
	}
}
