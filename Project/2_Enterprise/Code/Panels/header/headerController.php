<?phprequire_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');require_once('headerModel.php');require_once('headerView.php');require_once 'Project/Model/Posts/Post_Categories/post_categories.php';require_once 'Project/Model/Routes/route.php';

class headerController extends controllerSuperClass_Core{	public function getHeader()	{		$headerView = new headerView();		$headerView->getHeader();	}}
?>