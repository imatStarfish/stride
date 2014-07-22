<?php

class fileUpload
{
	public static function uploadMultiple($name = 'file', $accepted_types = array())
	{
		$file_array = array();
		$file_count = count($_FILES[$name]['name']);
		
		//i just rearranged the array
		for($i = 0; $i < $file_count; $i++)
			$file_array[] = array(
				'name'		=>$_FILES[$name]['name'][$i],
				'type'		=>$_FILES[$name]['type'][$i],
				'tmp_name'	=>$_FILES[$name]['tmp_name'][$i],
				'error'		=>$_FILES[$name]['error'][$i],
				'size'		=>$_FILES[$name]['size'][$i]
			);
		
		foreach($file_array as $file)
			self::upload($file, $path, '', $name, $accepted_types);
		
	}
	
//-------------------------------------------------------------------------------------------------	
	/*
	 * @author Raymart Marasigan
	 * @date 10/3/2013
	 * This method will do the uploading of the uploaded files.
	 * 
	 * @params file = this is the $_FILES array
	 * @params path = this will be the directory of the uploaded file e.g. C:/workspace  (no slash in the last)
	 * @params filename = this will be the file name of the uploaded file e.g. image1.jpg
	 * @params name = this will be the name of the input file element in DOM e.g. <input type="file" name="file"/> file will be the name
	 * @params accepted_types = this will be the array of mime accepted types e.g. array("application/pdf", "image/jpg")
	 * 
	 */
	
	public static function upload($file, $path, $filename, $name = 'file', $accepted_types = array())
	{
		//get the extension
		$ext = self::getExtension($filename);
		
		//check if it is an accepted file
		if(self::validateUpload($file, $name, $accepted_types))
		{
			//check if there is same filename in the directory
			if(!file_exists($path.'/'.$filename))
				return move_uploaded_file($file[$name]['tmp_name'], $path.'/'.$filename);
			else
				echo "File {$path}/{$filename} already exists.<br>";
		}
		else
			echo FALSE;
		
		return FALSE;
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public static function validateUpload($file, $name, $accepted_types = array())
	{
		//check if there is no error in $_FILES array
		if( $file[$name]["error"] == 0 )
		{
			//if accepted type is NULL it means all file is accepted
			if(count($accepted_types) == 0)
				return TRUE;
			else
			{
				//check if there accepted type is in the accepted types array
				if(in_array($file[$name]["type"], $accepted_types))
					return TRUE;
				else
					return FALSE;
			}
		}
	}
	
//-------------------------------------------------------------------------------------------------	
	
	private static function getExtension($filename)
	{
		$exploded_ext 	 = explode(".", $filename);
		$ext 			 = $exploded_ext[count($exploded_ext) - 1];
		return $ext;
	}
	
}
