<?php
class mail_check_mx {
	var $system_OS			= 'win';
	var $debug 				= 0;  		
	var $recType 			= "MX";		
	var $email;							
	var $hostName;						
	var $userName; 						
	var $check_DNS_result;				
	var $check_MAIL_result;				
	function __construct(){
		$this -> system_OS = strtoupper(substr(PHP_OS, 0, 3)) == "WIN" ? 'win' : 'linux'; 	
	}
	function checkDNS() 
		{ 
		if ($this -> system_OS == "linux") 		
			{
			if (@getmxrr($this -> hostName, $mxhosts)) return TRUE;
				else return FALSE;
			} else 	{							
					if(!empty($this -> hostName))
						{ 
						exec("nslookup -type=".$this->recType." ".$this -> hostName, $result); 
						if (preg_replace('#\s#','',$result[0]) == 'Server:UnKnown'){return true;}
						foreach ($result as $line) 
							{
							if(preg_match('/^'.$this -> hostName.'/i',$line)) return true; 
							} 
					   return false; 
						} 
					 return false; 
					}		
		}
	function check_email_dns()
		{
		list($this -> userName, $this -> hostName) = explode("@", $this -> email); 
		if (!$this -> checkDNS ($this -> hostName)) 
			{
			$this -> check_DNS_result = "Address domain MX DNS record could NOT be found";	
			return FALSE;
			} else 	{
					$this -> check_DNS_result = "Address DNS MX is OK";	
					return TRUE;
					}
		}
	function check_email()
		{

		$this -> email = strtolower($this -> email); 
		if (preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $this -> email)) 
			{
			$this -> check_MAIL_result = "Address syntax is OK";
			return TRUE;
			} else	{
					$this -> check_MAIL_result = "Address syntax is WRONG";	
					return FALSE;		
					}
		}
	function final_mail_check($email_address)
		{
		$this->email = $email_address;
		if (!$this -> check_email_dns() OR !$this -> check_email()) return FALSE;
			else return TRUE;
		}
	function debug_address()
		{
		if ($this -> debug == 1) 
			{
			echo "<br>";
			echo "<b>DEBUG</b>:";
			echo "<br>";
			echo "<u>eMail</u>: ".$this -> email;
			echo "<br>";
			echo "<u>DNS</u>: ".$this -> check_DNS_result;
			echo "<br>";
			echo "<u>Syntax</u>: ".$this -> check_MAIL_result ;
			echo "<br>";
			}
		}
	}
?>