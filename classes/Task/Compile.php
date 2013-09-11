<?php defined('SYSPATH') or die('No direct script access.');
 
class Task_Compile extends Minion_Task
{	
	protected function _execute(array $params)
    {
		$media_types = array(
			'styles',
			'scripts',
		);
		
		$content = array();
		
		foreach ($media_types as $type)
		{
			$config = Kohana::$config->load($type);
			
			$profiles = array();
			
			foreach ($config as $profile => $files)
			{
				$profiles[$profile] = array(
					'files' => array(),
					'combined' => '',
				);
				
				$files = Media::get_assets($profile, $type);
				
				$profiles[$profile]['files'] = $files;
				
				foreach ($files as $file)
				{
					if ($file['priority'] <= $type::CUTOFF)
					{
						$path = Kohana::find_file(Media::MEDIA_FOLDER, $type::get_path($file), $type::EXT);
						$profiles[$profile]['combined'] .= file_get_contents($path);			
					}
				}
								
				// Filter
				$output = Filter::minify($profiles[$profile]['combined'], $type);
				
				// Custom Filter, if any
				$output = Filter::custom($output);
				
				$output_folder = APPPATH.'../media';
				
				// Spit out the beautiful files
				file_put_contents($output_folder.'/'.$profile.'.'.$type::EXT, $output);
			}
			
			$content[$type] = $profiles;
		}
		$view = new View('minion/compile');

		$view->content = $content;

		echo $view;
	}
}
