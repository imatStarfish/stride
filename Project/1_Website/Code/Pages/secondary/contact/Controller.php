<?phprequire_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/controllerSuperClass_Core/controllerSuperClass_Core.php';require_once('Model.php');require_once('View.php');require_once 'Project/1_Website/Code/Modules/mailer.php';require_once 'Project/Model/Settings/settings.php';
class controller extends controllerSuperClass_Core
{	public function indexAction()	{				$dataHandler = new dataHandler();		$contentXML = $dataHandler->loadDataSimpleXML('Data/1_Website/Content/Pages/secondary/contact/data.xml');						metaData::getInstance()->extractMetaData($contentXML);		metaData::getInstance()->getMetaData();				$view = new view();		$view->renderAll();	}		public function submitEmailAction()	{		$sanitized_data = $this->sanitizePost($_REQUEST);				if ($sanitized_data)		{			$settings	=	new settings();			$settings->select();			$to			=	$settings->__get('to_email');			$subject	=	'Starfish Website Inquiry';			$message	=	"Name: ".$sanitized_data['name'];			$message	.=	"<br />Company: ".$sanitized_data['company'];			$message	.=	"<br />Email Address: ".$sanitized_data['email_address'];			$message	.=	"<br />Contact Number: ".$sanitized_data['contact_number'];			$message	.=	"<br /><br />Message: ".$sanitized_data['message'];						$header = "					From: Starfish Website Contact Form					";									mailer::send_email("", "", $sanitized_data['email_address'], $sanitized_data['name'], $subject, $message);			jQuery::getResponse();		}	}		private function sanitizePost($post)	{		$sanitized_result = array();			if (!empty($post))		{			foreach ($post as $index => $data)			{				if (strpos($index, 'email') !== FALSE)				{					if (filter_var($data, FILTER_VALIDATE_EMAIL) === FALSE)					{						return FALSE;					}					else					{						$sanitized_result[$index] = filter_var($data, FILTER_SANITIZE_EMAIL);					}				}				else				{					$sanitized_result[$index] = filter_var($data, FILTER_SANITIZE_STRING);				}			}							return $sanitized_result;		}		else		return FALSE;	}	}?>