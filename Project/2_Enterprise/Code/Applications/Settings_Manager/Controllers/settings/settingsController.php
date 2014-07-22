<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Model/Settings/settings.php';
require_once 'settingsView.php';

class settingsController extends applicationsSuperController
{
	public function indexAction()
	{	
		$model = new settings();
		$model->select();
		
		if(isset($_POST['saveBtn']) == 'Save')
		{
			if($_POST['password'] == '' || $_POST['password'] == NULL){
				$model->__set('username', $model->__get('password'));
			}
			else
				$model->__set('password', sha1(md5($_POST['password'])));
			
			$model->__set('username', $_POST['username']);
			$model->__set('smtp_host', $_POST['smtp_host']);
			$model->__set('smtp_username', $_POST['smtp_user']);
			$model->__set('smtp_password', $_POST['smtp_pass']);
			$model->__set('smtp_port', $_POST['smtp_port']);
			$model->__set('use_smtp', TRUE);
			$model->__set('smtp_auth', TRUE);
			$model->__set('to_email', $_POST['to_email']);
			$model->__set('to_name', $_POST['to_name']);
			$model->__set('google_analytics', $_POST['g_analytics']);
			$model->__set('google_verification', $_POST['google_verification']);
			$model->update();
		}
		
		$view = new settingsView();
		$view->setArrayOfResults($model);
		$view->displaySettingsContentColumn();
	}
}


