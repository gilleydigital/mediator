<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Media {
	const MEDIA_FOLDER = 'media';

	protected static $_buffer = array(
		'scripts' => array(),
		'styles' => array(),
	);

	// Add the specified media to the buffer
	public static function add ($name, $priority, $type)
	{
		Media::$_buffer[$type][] = array($name, $priority);
	}

	public static function get_assets ($profile, $type)
	{
		// Add from config
		$config = Kohana::$config->load($type);
		
		$from_config = $config->get($profile) ? $config->get($profile) : array();
		
		// Add from plugins
		$plugins_config = Kohana::$config->load('plugins');
		$schemas_config = Kohana::$config->load('plugin/blueprints');
		
		$plugins = $plugins_config->get($profile) ? $plugins_config->get($profile) : array();
		
		$from_plugins = array();
		foreach ($plugins as $plugin)
		{
			$from_this_plugin = $schemas_config[$plugin][$type];
			if (is_array($from_this_plugin))
			{
				foreach ($from_this_plugin as $file)
				{
					$from_plugins[] = $file;
				}
			}
		}
				
		// Add from buffer
		$from_buffer = Media::$_buffer[$type];
		// Clear the buffer
		Media::$_buffer[$type] = array();

		$assets = array_merge($from_config, $from_plugins, $from_buffer);
		$assets = Media::prepare($assets);

		return $assets;
	}

	// Sort and filter out doubles
	protected static function prepare (array $input)
	{
		$sort_me = array();
		$doubles = array();
		
		// Filter and add stubs to $sort_me
		foreach ($input as $key => $subarray)
		{
			// Check for name doubles
			if ( ! in_array($subarray[0], $doubles))
			{
				// key => priority
				$sort_me[$key] = $subarray[1];
				// add the name to doubles array
				$doubles[] = $subarray[0];
			}
		}

		// sort by priority, giving you the keys in desired order
		asort($sort_me);

		$return_me = array();

		// Add the full arrays to $return_me
		foreach ($sort_me AS $key => $priority)
		{
			// Move things around to make it a little nicer to work with
			$file = Media::parse($input[$key][0]);
			
			$add_me = array(
				'prefix' => $file['prefix'],
				'name' => $file['name'],
				'priority' => $input[$key][1],
			);
			
			$return_me[] = $add_me;
		}
		
		return $return_me;
	}
	
	// Splits a string into prefix & string
	protected static function parse ($name)
	{
		// $name will be either 'string' or 'prefix/string'
		$file = array();

		// Check for slash
		if (strpos($name, '/') !== FALSE)
		{
			$pieces = explode('/', $name);

			$file['name'] = array_pop($pieces);
			$file['prefix'] = implode('/', $pieces).'/';
		}
		else
		{
			$file['prefix'] = '';
			$file['name'] = $name;
		}
		
		return $file;
	}
}
