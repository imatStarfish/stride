<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');

class mainAppView extends viewSuperClass_Core
{
	public function displayApplicationMainLayout()
	{
		$currentApplicationID = applicationsRoutes::getInstance()->getCurrentApplicationID();
		
		$content = $this->renderTemplate('Project/'.DOMAIN.'/Design/Applications/'.$currentApplicationID.'/Main_App_Layout/templates/inpage_javascript.js');
		response::getInstance()->addContentToStack('local_javascript_bottom',array('PHOTOLIBRARY_JS'=>$content));
		
		$content = $this->renderTemplate('Project/'.DOMAIN.'/Design/Plugins/dropzone/dropzone.min.js');
		response::getInstance()->addContentToStack('local_javascript_bottom',array('DROPZONE'=>$content));
		
		$content = $this->renderTemplate('Project/'.DOMAIN.'/Design/Applications/'.$currentApplicationID.'/Main_App_Layout/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('local_css',array('PHOTOLIBRARY_CSS'=>$content));
		
		$content = $this->renderTemplate('Project/'.DOMAIN.'/Design/Applications/'.$currentApplicationID.'/Main_App_Layout/templates/main_app_layout.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
}
?>