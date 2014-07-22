<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
//require_once 'pages_editor_View_Renderer.php';

class pages_editor_View  extends applicationsSuperView
{
	private $templates_location;
	private $album_id;
	private $size_id;
	
	private $array_of_xml = array();
	
//-------------------------------------------------------------------------------------------------	
	
	public function __construct()
	{
		$this->templates_location = 'Project/2_Enterprise/Design/Applications/Content_Management_System/Controllers/pages_editor/templates/';
		
		$content = $this->renderTemplate('Project/2_Enterprise/Design/Applications/Content_Management_System/Controllers/pages_editor/templates/inpage_javascript.js');
		response::getInstance()->addContentToStack('local_javascript_bottom',array('PAGE EDITOR CS JS'=>$content));
		
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	
	
	public function displayPageEditor($pageXML) 
	{
		//render the DOM structure
		xmlProcessor::getInstance()->traverseDOM($pageXML,$this,'renderDefaultDOM');
		$content = $this->renderTemplate($this->templates_location.'pages_editor_form.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function renderDefaultDOM($nodeName,$nodeValue,$attributes,$dom,$startOrEndTag)
	{
		$xml_array = array();
		
		$xml_array['name']			= $nodeName;
		
		//remove CDATA and unnecessary tags
		$nodeValue = str_replace('<![CDATA[', '', $nodeValue);
		$nodeValue = str_replace(']]>', '', $nodeValue);
		
		$xml_array['value'] 		= $nodeValue;
		
		$image_id = 0;
		if (isset($attributes['id']))
			$image_id = $attributes['id'];
		
		//get image details
		$model = new pages_editorModel();
		$image_details = $model->getImageDetails($image_id);
		$image_path = "{$image_details["album_folder"]}/{$image_details["dimensions"]}_thumb/{$image_details["path"]}";
		
		$xml_array['image_path'] 	= $image_path;
		$xml_array['attributes']	= $attributes;
		$xml_array['tag']			= $startOrEndTag;
		
		$this->array_of_xml[] = $xml_array;
	}
	
}