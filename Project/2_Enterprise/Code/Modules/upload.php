<?php
/**
* @author Raymart Marasigan
* @date 10/3/2013
* @des - This class will do the uploading of the uploaded files.
*/

class uploader
{
	
	/**
	* @des - this will do the multiple uploading of the file
	* @params array  $file - this is the $_FILES array
	* @params string $path - this will be the directory of the uploaded file e.g. C:/workspace  (no slash in the last)
	* @params string $name - this will be the name of the input file element in DOM e.g. <input type="file" name="file"/> file will be the name
	* @params array  $accepted_types - this will be the array of mime accepted types e.g. array("application/pdf", "image/jpg")
	* if accepted_types is an empty array it means it will accept all the file type.
	*
	* @return array - an array consisting of all successfuly uploaded file
	*
	*/
	
	public static function uploadMultiple($file, $path, $name = 'file', $accepted_types = array())
	{
		$uploaded_files = array();
		$file_count     = count($file[$name]["name"]);
		
		for($i = 0;  $i < $file_count;  $i++)
		{
			//image details
			$ext 	  = self::getExtension($_FILES[$name]['name'][$i]);
			
			//filename should be uniq to avoid error in existing file
			$filename = uniqid().rand(1, 999).".".$ext;
			
			//image details must be in normal form of $_FILES array
			$file_details = array(
				$name => array(
					"name" 		=> $_FILES[$name]['name'][$i],
					"type" 		=> $_FILES[$name]['type'][$i],
					"tmp_name"  => $_FILES[$name]['tmp_name'][$i],
					"error"		=> $_FILES[$name]['error'][$i],
					"size"		=> $_FILES[$name]['size'][$i]
				)
			);
			
			//upload the file
			$upload = self::upload($file_details, $path, $filename, $name);

			//if uploading is succesfull put the details on $uploade_files
			if($upload)
			{
				$uploaded_files[] = array(
					"filename"            => $filename,
					"original_filename"   => $file[$name]["name"][$i],
					"size"				  => $file[$name]["size"][$i],
					"path"				  => $path,
					"ext"				  => $ext
				);
			}
		}
		
		if($uploaded_files)
			return $uploaded_files;
		else
			return false;
	}
	
	//-------------------------------------------------------------------------------------------------

	/**
	* @des - this will do the uploading of the file
	* @params array  $file - this is the $_FILES array
	* @params string $path - this will be the directory of the uploaded file e.g. C:/workspace  (no slash in the last)
	* @params string $filename - this will be the file name of the uploaded file e.g. image1.jpg
	* @params string $name - this will be the name of the input file element in DOM e.g. <input type="file" name="file"/> file will be the name
	* @params array  $accepted_types - this will be the array of mime accepted types e.g. array("application/pdf", "image/jpg")
	* if accepted_types is an empty array it means it will accept all the file type.
	* 
	* @return boolean - true/false
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
				return "File {$path}/{$filename} already exists.<br>";
		}
		else
			return "File is not supported.";

		return FALSE;
	}

	//-------------------------------------------------------------------------------------------------
	/** 
	 * @des - this method willb validate if the file is valid to be uploaded
	 * @params array  $file - this is the $_FILES array
	 * @params string $name - this will be the name of the input file element in DOM e.g. <input type="file" name="file"/> file will be the name
	 * @return boollean - true/false
	 */
	
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
	/*
	 * @des - extract the file extensio of a file
	 * @params string $filename - file name of the file. e.g. imat.png 
	 */

	private static function getExtension($filename)
	{
		$exploded_ext 	 = explode(".", $filename);
		$ext 			 = $exploded_ext[count($exploded_ext) - 1];
		return $ext;
	}

}
