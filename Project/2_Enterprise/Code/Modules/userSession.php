<?php 
/**
 * @autor Raymart Marasigan
 * @date 2/11/2014
 * @des - this class will handle single session called user_session
 * @dependencies - Zend session
 * 
 */

require_once 'Zend/Session.php';

class userSession
{
	/**
	 * @des - save the session of your choice
	 * @params array $sessions - the array of session to 
	 * be saved e.g. array("username" => "imat", "role" => "admin");
	 * @note - notice that there is $user_session->logged_in, can be used when validating if 
	 * user session is existing
	 * 
	 */
	
	public static function saveSession($sessions) 
	{
		Zend_Session::regenerateId();
		Zend_Session::rememberMe(1728000);
		
		$user_session = new Zend_Session_Namespace("user_session");
		$user_session->logged_in = TRUE;
	
		foreach($sessions as $index => $value)
			$user_session->$index = $value;
	}
	
	//---------------------------------------------------------------------------------------------

	/**
	 * @des - extract the session value to your session
	 * @params string $session - the index of the session you want to extract
	 * e.g if you have array("username" => "imat", "role" => "admin"); if you put "username" to params 
	 * it will return "imat"
	 * @return - the session value you put on params.
	 * 
	 */
	
	public static function getSession($session)
	{
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info = unserialize(serialize($user_info));
		return $user_info->$session;
	}
	
	//---------------------------------------------------------------------------------------------
	
	/**
	* @des - get the content of your session variable
	* @return - the content of your session variable
	*
	*/
	
	public static function getWholeSession()
	{
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info = unserialize(serialize($user_info));
		return $user_info;
	}
	
	//---------------------------------------------------------------------------------------------

	/**
	* @des - change the value of your chosen session
	* @params string $session - the index of the session you want to update
	* @params string $value - the value of session you want to update
	* e.g if you have array("username" => "imat", "role" => "admin"); if you put "username" to $session
	* and "imat_marasigan" to $value it will change the value of session 
	* variable to array("username" => "imat_marasigan", "role" => "admin");
	* 
	*/
	
	public static function updateSession($session, $value)
	{
		Zend_Session::regenerateId();
		
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info->$session = $value;
	}
	
	//---------------------------------------------------------------------------------------------

	/**
	 * @des - simply destroy your session
	 */
	
	public static function unsetSession()
	{
		$user_session = new Zend_Session_Namespace("user_session");
		$user_session->unsetAll();
	}
}