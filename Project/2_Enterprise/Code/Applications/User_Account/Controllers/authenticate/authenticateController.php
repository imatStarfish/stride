<?phprequire_once "Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php";require_once "authenticateView.php";require_once "Project/Model/Settings/settings.php";require_once "Project/2_Enterprise/Code/Modules/userSession.php";class authenticateController extends applicationsSuperController{	public function loginAction()	{		if (isset($_POST["login"]))		{			$username = $_POST["username"];			$password = sha1(md5($_POST["password"]));						$credentials = settings::selectLogin($username, $password);						if($credentials)			{				$user_account["role"]     = "admin";				$user_account["user_id"]  = $credentials["settings_id"];				$user_account["username"] = $credentials["username"];				userSession::saveSession($user_account);				header("Location: /");			}		}		else		{			header("Location: /");		}				//echo of javascript here avoid instantiation of mainView		//since login templates is rendered on mainController		//it also retains the MVC structure		echo '<script>					window.onload = function() {						$(".error").html("Invalid Username or Password");					};			 </script>		';	}		public function logoutAction()	{		userSession::unsetSession();		header("Location: /");	}}