<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Faxdata extends CI_Model
{
	public function get($dir_path, &$files)
	{
		if (is_dir($dir_path) && $dh = opendir($dir_path))
		{
			while (($file = readdir($dh)) !== false)
			{
				$file_type = filetype($dir_path . $file);
				$file_date = filemtime($dir_path . $file);
				$shop_code = substr($dir_path, 22, -1);
				$cur_date = mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"));
				
				if ($file_type == "dir" && $file != "." && $file != "..")
				{
					$this->get($dir_path . $file . "/", $files);
				}
				if ($file_type == "file")
				{
					if ($file_date < $cur_date)
					{
						unlink($dir_path . $file);
						continue;
					}
					$temp['name'] = $file;
					$temp['path'] = $dir_path . $file;
					$temp['shop_code'] = $shop_code;
					$temp['time'] = filemtime($dir_path . $file);
					array_push($files, $temp);
				}
			}
			closedir($dh);
		}
		rsort($files);
		return;
	}

}
?>