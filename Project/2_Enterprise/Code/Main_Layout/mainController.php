<?phprequire_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/controllerSuperClass_Core/controllerSuperClass_Core.php';require_once('mainModel.php');require_once('mainView.php');require_once 'Project/2_Enterprise/Code/Modules/userSession.php';class mainController extends controllerSuperClass_Core{	public function getMainLayout()	{		$mainView = new mainView();		//check if the user has existing session		if(userSession::getSession("logged_in"))		{			$this->getHeader();			$this->getFooter();		}		//do login if session is not existing		else		{			$mainView->displayLoginForm();		}				$mainView->getMainLayout();	}		private function getHeader()	{		require_once 'Project/'.DOMAIN.'/Code/Panels/header/headerController.php'; 		$headerView = new headerController(); 		$headerView->getHeader();	}	private function getFooter()	{		require_once 'Project/'.DOMAIN.'/Code/Panels/footer/footerController.php';		$footerController = new footerController();		$footerController->getFooter();	}	}?>