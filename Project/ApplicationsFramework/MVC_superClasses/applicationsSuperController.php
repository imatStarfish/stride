<?php
require_once 'applicationsSuperView.php';

class applicationsSuperController
{
	private  $_parametersArray = array();
	
	public function __construct()
	{
		$this->_parametersArray = request::getInstance()->getParametersArray();
	}
	
	
	public function getParametersArray()
	{
		return request::getInstance()->getParametersArray();
	}
	
	public function getValueOfURLParameterPair($parameterPairKey)
	{
		//DOES NOT WORK WITH AJAX -- USE A &a=b instead
		//PARAMETERS ARE PASSED IN PAIRS eg /page/2/page/introduction/
		//i.e page = 2, page = 'introduction';
	
	
		//this line was added June 25 2009 because if the controller is called
		//againg it forgets the parameters array,
		//$this->_parametersArray = request::getInstance()->getParametersArray();
		
		//Revised by Raymart Marasigan 10/25/2013
		//This will help in getting the parameter next to the controllers
		$this->_parametersArray = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
		
		
		if (in_array($parameterPairKey,$this->_parametersArray))
		{
			$count = array_search($parameterPairKey,$this->_parametersArray);
				
			if(isset($this->_parametersArray[$count+1])) {
				return $this->_parametersArray[$count+1];
			}
			else return null; //parameter wrong or missing
		}
		else
		{
			return null;
		}
	}
	
	
	//====================================================================================================================
	//===			 PRE DISPATCH
	//====================================================================================================================
	public function predispatch()
	{
		//NOTE THIS FUNCTION ISNT CALLED AND CANNOT BE CALLED BY THE DISPATCHER PREDISPATCH FUNCTION
		//BECAUSE THIS CONTROLLER DOESNT EXIST UNTIL AFTER THE DISPATCHER CLASS DISPATCHES IT
		//ITS JUST HERE SO I REMEMBER NOT TO TRY AND PUT THINGS HERE AGAIN!
	}
	//====================================================================================================================
	//===			 DISPATCH
	//====================================================================================================================
	//called from dispatcher
    public function speculativeDispatch() {
    	//there is one final choice to make in the dispatch process
    	//Its a speculative dispatch
    	//Because not all Actions  need to be specified in the routes xml (timeconsuming)
    	//only the ones that need a Site Map, Breadcrumbs, Nav 
    	//There is a time when what is thought of as a parameter is an unspecified action
    	//First try this and if that fails try the given $action,
    	//the action will use
		$content='';
		
		
		$is_ajax = explode("/", ltrim($_SERVER["REQUEST_URI"], "/"));
		
		//Raymart Marasigan
		//September 9, 2013
		//This method is added to make the ajax fixed
		
		if($is_ajax[0]  == "ajax")
			$speculativeAction = $is_ajax[count($is_ajax) - 1].'Action';
		else
			$speculativeAction = $this->_parametersArray['0'].'Action';
		
    	if (method_exists($this,$speculativeAction)) 
    	{
	    	try
	        {
	    		array_shift($this->_parametersArray);
	    		$content = $this->$speculativeAction();
		    }
	    	catch (Exception $e) 
	        {
	       		throw $e;
	    	}
	    	return $content;
    	}
    	else 
    	{
    		if($this->checkIfPermalink($this->_parametersArray['0']) || $this->checkIfInApplicationRouting($this->_parametersArray['0']))
			{
					$this->normalDispatch();
					return $content;
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
				header("Location: /404");
			}    	
    	}
    }
    
    
    //---------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------
    //Raymart Marasigan
    //September 4, 2013
    //this method is used to check if the url is in the application routing
    //to trigger the 404 redirection
    private function checkIfInApplicationRouting($controller)
    {
    	$xml_obj          = simplexml_load_file("Project/".DOMAIN."/Code/Applications/applications_routing.xml");
    	$in_application   = $xml_obj->xpath('//url_name[text()="'.$controller.'"]');
    
    	if(count($in_application) > 0)
    	return TRUE;
    	else
    	return FALSE;
    }
    
    
    //---------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------
    //Raymart Marasigan
    //September 4, 2013
    //this method is used to check if the controller (e.g indexAction) is in the routing table
    //to trigger the 404 redirection
    private function checkIfPermalink($permalink)
    {
    	$pdoConnection = starfishDatabase::getConnection();
    	$sql = "SELECT
    					*
    				FROM 
    					route
    				WHERE 
    					permalink = :permalink
    				LIMIT 0,1";
    		
    	$pdoStatement = $pdoConnection->prepare($sql);
    	$pdoStatement->bindParam(":permalink", $permalink, PDO::PARAM_STR);
    	$pdoStatement->execute();
    	$result = $pdoStatement->fetch();
    
    	if($result == TRUE)
    	return TRUE;
    	else
    	return FALSE;
    }
    
    
    
    
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------    
    public function normalDispatch() {
     try {
    			$content = $this->indexAction();
    	}
    	catch (Exception $e){
            throw $e;
    	}
    	return $content;
    }
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------  
    //---------------------------------------------------------------------------------------------------------------
	private function sanitizePOST() {
		//to prevent MySQL and XSS attacks
		global $_POST;
		if(isset($_POST)) {
			foreach($_POST as $key=>$value) {
				$_POST[$key] = stripslashes($value);
			}
		}
	}
	//=======================================================================================	
	public function doLogin()
	{
		//this PUBLIC function is the standard login but can be over ridden by the child application controller
		//which must be a PUBLIC function for this to work.
		
		require(STAR_SITE_ROOT.'/Project/'.DOMAIN.'/Code/Applications/User_Account/Controllers/login/loginController.php');
		$loginController = new loginController();
		$loginController->indexAction();
	}
	
	
	//====================================================================================================================
	//===			 POST DISPATCH
	//====================================================================================================================
	public function postdispatch()
	{
		
		if (applicationsRoutes::getInstance()->getHasMainLayout()=="yes")
		{
			$currentApplicationID = applicationsRoutes::getInstance()->getCurrentApplicationID();
			require_once('Project/'.DOMAIN.'/Code/Applications/'.$currentApplicationID.'/Main_App_Layout/mainAppController.php');
			$mainAppController = new mainAppController();
			$mainAppController->getMainLayout();
		}
	}
}