<?php 

/**
 * @author Raymart Marasigan
 * @date 10/3/2013
 * @des this class will do the email funtcionality of the website
 * @dependencies -  settings.php, PHPMailerAutoload.php;
 * 
 */

require_once 'Project/Model/Settings/settings.php';
require_once 'Project/1_Website/Code/Modules/phpmailer/PHPMailerAutoload.php';

class mailer
{
 	
 	public static $mime_type = array(
 	 	 			"doc"  		=> 	  "application/msword",
 	 	 			"docx"  	=> 	  "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
 	 	 			"pdf"  		=> 	  "application/pdf",
 	 	 			"xlsx"		=> 	  "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
 					"gif"		=> 	  'image/gif',
 					"jpg"       =>    'image/jpeg',
 					"png"	    =>    'image/png',
	 				"txt"       =>	  'text/plain',
 					"rtf"       =>    'application/rtf'
 	);
 	
 	/**
 	 * @des    - return mime type base on file extension
 	 * @notes  - I use this because some of the php function that get the mimetype
 	 *           is not working on other php version
 	 * @params string $filename  - full filename of the file e.g. (imat.png)      
 	 * @return - mime type of the file
 	 * @requiremetns array $mime_type - an array of file extension with equivalent mime type        
 	 */
 	
 	private static function getMimeType($filename)
 	{
 		$filename = basename($filename);
 		$ext = explode(".", $filename);
 		$ext = $ext[count($ext) - 1];
 	
 		return self::$mime_type[$ext];
 	}
 	
 	//------------------------------------------------------------------------------------------------------------------

	/**
	 * @params array $from - array of sender details e.g. array("email" => "raymart.marasigan@starfis.h", "name" => "Raymart")
	 * @params array $to - array of recipient e.g. array(0 => array("email" => "raymart.marasigan@starfis.h", "name" => "Raymart"))
	 * @params string $subject - subjec of the email
	 * @params string $body - body of the email
	 * @params array $attachments - an array of attachment e.g. array([0] => array("path" => "public_html/imat.com/data/imat.jpg", "filename" => "imat.jpg"))
 	 * #the attachments["path"] must be an ABSOLUTE PATH
 	 * @params array $cc - carbon copy of the email e.g. array("imat@me.com", "rayamart@starfi.sh")
 	 * @params array $bcc - block carbon copy of the email e.g. array("imat@me.com", "rayamart@starfi.sh")
 	 * @params string $alt_body - body of the email if the client's platform is not supported by html
 	 * 
 	 * @return boolean
 	 * 
	 */
	
	public static function send_with_phpmailer($from, $subject, $body, $to = NULL, $attachments = NULL, $cc = NULL, $bcc = NULL, $alt_body = NULL)
	{
		//select the enterprise settings
		$settings	= new settings();
		$settings->select();
		
		//if receiver is not specified get it to the database
		if($to == NULL)
			$to[] = array("email" => $settings->__get("to_email"));
		
		$mail = new PHPMailer;
		
		//define settings
		$mail->isSMTP();
		$mail->Host 	= $settings->__get("smtp_host");
		$mail->SMTPAuth = true;
		$mail->Username = $settings->__get("smtp_username");
		$mail->Password = $settings->__get("smtp_password");;
		
		//define sender
		$mail->From 	= $from["email"];
		$mail->FromName = isset($from["name"]) ? $from["name"] : "";
		
		//define recipients
		foreach($to as $recipient)		
			$mail->addAddress($recipient["email"], isset($recipient["name"]) ? $recipient["name"] : ""); 
		
		//define attachments
		if($attachments)
		{
			foreach($attachments as $attachment)
				$mail->addAttachment($attachment["path"], $attachment["filename"]);
		}
		
		//define cc
		if($cc)
		{
			foreach($cc as $email)
				$mail->addCC($email);
		}
		
		if($bcc)
		{
			//define bcc
			foreach($bcc as $email)
				$mail->addBCC($email);
		}
		
		$mail->isHTML(true);
		$mail->Body 	= $body;
		$mail->Subject  = $subject;
		$mail->AltBody  = $alt_body;
		
		$send = $mail->send();
		//return $mail->ErrorInfo;
		return $send;
	}
	
	//------------------------------------------------------------------------------------------------------------
	
	/**
	 * @des - a generic mailer that send a html content email
	 * @params string $from_email - sender of the email that will appear in FROM of email
	 * if you want to include name of the sender e.g. Raymart <raymart.marasigan@starfi.sh>, Imat <imat@gmail.com>
	 * @params string $subject - subject of the email
	 * @params string body - body of the email
	 * @params string to - the receipeint of the email
	 * if you want a multiple recipient e.g. raymart.marasigan@starfi.sh, 
	 * if you want a multiple recipient e.g. raymart.marasigan@stafi.sh, imat@gmail.com 
	 * if you want to include name of the reciepeint e.g. Raymart <raymart.marasigan@starfi.sh>, Imat <imat@gmail.com>
	 * @params string cc - cc of the email, the value can be the value of the $to params
	 * @return boolean - true/false
	 */

 	public static function sendEmail($from_email, $subject, $body, $to = NULL, $cc = NULL)
	{
		$settings  = new settings();
		$settings->select();
		
		ini_set("SMTP",$settings->__get("smtp_host"));
	
		//if receiver is not specified get it to the database
		if($to == NULL)
			$to = $settings->__get("to_email");						
	
		
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html;";
		$headers[] = "From: $from_email";
		
		if($cc != NULL)
			$headers[] = "CC: $cc";
		
		$headers[] = "Subject: $subject";
		$headers[] = "X-Mailer: PHP/".phpversion();

		$headers = implode("\r\n", $headers);
		return @mail($to, $subject, $body, $headers);		
	}
	
}