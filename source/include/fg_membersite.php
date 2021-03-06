<?PHP
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once("class.phpmailer.php");
require_once("formvalidator.php");


class FGMembersite
{
    var $admin_email;
    var $admin2_email;
    var $from_address;
    
    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;
    
    var $error_message;
    
    //-----Initialization -------
    function FGMembersite()
    {
        $this->sitename = 'localhost';
        $this->rand_key = '0iQx5oBk66oVZep';
    }
    
    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;

    }
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }
    
     function SetAdmin2Email($email)
    {
        $this->admin2_email = $email;
    }
    
    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
    
    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }

        $this->SendAdminIntimationEmail($formvars);
        
        return true;
    }
	
    // ------------- Code to register user order to db.----------------------------------------------
	   
    function RegisterUserOrder()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        $formvars = array();
        
        $this->CollectOrderSubmission($formvars);
        
        if(!$this->SaveOrderToDatabase($formvars))
        {
            return false;
        }
        
        // Here is the place to put a conformation e-mail function
        //if(!$this->SendUserConfirmationEmail($formvars))
        //{
        //    return false;
        //}
        $this->SendAdminIntimationEmail($formvars);
        
        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Please provide the confirm code");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }
        
        $this->SendUserWelcomeEmail($user_rec);
        
        $this->SendAdminIntimationOnRegComplete($user_rec);
        
        return true;
    }    
    
    function Login()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        

        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }
        
        $_SESSION[$this->GetLoginSessionVar()] = $username;

        return true;
    }
    
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    
    function UserFullName()
    {

        !$this->DBLogin();
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'none';
    }
    
    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'none';
    }
	function UserPhoneNumber()
	
    {
	    return isset($_SESSION['phone_of_user'])?$_SESSION['phone_of_user']:'none';
		
    }
	function isAdmin()
	{

        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }  

        $admin_email = $_SESSION['email_of_user'];

	    return strcmp('anastassia.ivanova.94@gmail.com', $admin_email);

	}
    
    function LogOut()
    {
        session_start();
        
        $sessionvar = $this->GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;
        
        unset($_SESSION[$sessionvar]);
    }
    
    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code is empty!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
        
        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }
        
        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }
        
        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
        return true;
    }
    
    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }
        
        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Old password is empty!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("New password is empty!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }
        
        $pwd = trim($_POST['oldpwd']);

    	$salt = $user_rec['salt'];
        $hash = $this->checkhashSSHA($salt, $pwd);
        
        if($user_rec['password'] != $hash)
        {
            $this->HandleError("The old password does not match!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);
        
        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }
    
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }    
    
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }
    
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlierror:".mysqli_error($this->connection));
    }
    
    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    
    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }          
        $username = $this->SanitizeForSQL($username);

  	$nresult = mysqli_query($this->connection, "SELECT * FROM $this->tablename WHERE username = '$username'") or die(mysqli_error($this->connection));
        // check for result 
        $no_of_rows = mysqli_num_rows($nresult);
  
            $nresult = mysqli_fetch_array($nresult);
            $salt = $nresult['salt'];
            $encrypted_password = $nresult['password'];
            $hash = $this->checkhashSSHA($salt, $password);
         
           

        $qry = "Select name, email, phone_number, id_user from $this->tablename where username='$username' and password='$hash'";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The username or password does not match");
            return false;
        }
        
        $row = mysqli_fetch_assoc($result);
        
        
        $_SESSION['name_of_user']  = $row['name'];
        $_SESSION['email_of_user'] = $row['email'];
		$_SESSION['phone_of_user'] = $row['phone_number'];
		$_SESSION['id_of_user']  = $row['id_user'];

	    mysqli_free_result($result);
        
        return true;
    }

    function CheckLoginInDB_Hybrid($identifier)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }        
               
        $qry = "Select name, email, phone_number, id_user from $this->tablename where hybridauth_provider_uid = '$identifier'";
        
        $result = mysqli_query($this->connection,$qry);
        
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The username or password does not match");
            return false;
        }
        
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['id_of_user']  = $row['id_user'];
        $_SESSION['name_of_user']  = $row['name'];
        $_SESSION['email_of_user'] = $row['email'];
		$_SESSION['phone_of_user'] = $row['phone_number'];

	    mysqli_free_result($result);
        
        return true;
    }

    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
    
    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $confirmcode = $this->SanitizeForSQL($_GET['code']);
        
        $result = mysqli_query($this->connection,"Select name, email from $this->tablename where confirmcode='$confirmcode'");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Wrong confirm code.");
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];
        
        $qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";
        
        if(!mysqli_query($this->connection, $qry ))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }      
        return true;
    }
    
    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,10);
        
        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }
    
    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);

        $hash = $this->hashSSHA($newpwd);

	$new_password = $hash["encrypted"];

	$salt = $hash["salt"];
        
        $qry = "Update $this->tablename Set password='".$new_password."', salt='".$salt."' Where  id_user=".$user_rec['id_user']."";
        
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }     
        return true;
    }
    
    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $email = $this->SanitizeForSQL($email);
        
        $result = mysqli_query($this->connection,"Select * from $this->tablename where email='$email'");  

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }
        $user_rec = mysqli_fetch_assoc($result);

        
        return true;
    }
    
    function SendUserWelcomeEmail(&$user_rec)
    {
		require 'PHPMailerAutoload.php';
		$mailer = new PHPMailer;
		//$mailer->SMTPDebug = 2;
		//$mailer->Debugoutput = 'html';
        
        $mailer->CharSet = 'utf-8';
		$mailer->IsSMTP();
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port = 587;
		$mailer->SMTPSecure = 'tls';
		$mailer->SMTPAuth = TRUE;
		$mailer->Username = 'lkcmailer@gmail.com';  
		$mailer->Password = 'lkconsulting';
        $mailer->addReplyTo('lkcmailer@gmail.com', 'First Last');  
        $mailer->AddAddress($user_rec['email'],$user_rec['name']);
        
        //$mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->setFrom($this->GetFromAddress(),"lkcmailer");
		

        
        $mailer->Subject = "Welcome to ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Welcome! Your registration  with ".$this->sitename." is completed.\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Failed sending user welcome email.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Registration Completed: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$user_rec['name']."\r\n".
        "Email address: ".$user_rec['email']."\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function GetResetPasswordCode($email)
    {
       return substr(md5($email.$this->sitename.$this->rand_key),0,10);
    }
    
    function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email));

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Your new password for ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
        $validator->addValidation("name","req","Please fill in Name");
        $validator->addValidation("email","email","The input for Email should be a valid email value");
        $validator->addValidation("email","req","Please fill in Email");
        $validator->addValidation("phone_number","req","Please fill in Phone_number");
        $validator->addValidation("password","req","Please fill in Password");

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
	
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
	    $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
		$formvars['phone_number'] = $this->Sanitize($_POST['phone_number']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
   
    }

    // ---------------- Code for order submission -------------------

    function CollectOrderSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['phone_number'] = $this->Sanitize($_POST['phone_number']);
        $formvars['order'] = $this->Sanitize($_POST['order']);
   
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
		require 'PHPMailerAutoload.php';
		$mailer = new PHPMailer;
		//$mailer->SMTPDebug = 2;
		//$mailer->Debugoutput = 'html';
        
        $mailer->CharSet = 'utf-8';
		$mailer->IsSMTP();
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port = 587;
		$mailer->SMTPSecure = 'tls';
		$mailer->SMTPAuth = TRUE;
		$mailer->Username = 'lkcmailer@gmail.com';  
		$mailer->Password = 'lkconsulting';
        $mailer->addReplyTo('lkcmailer@gmail.com', 'First Last');  
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);
        
        //$mailer->Subject = "Your registration with ".$this->sitename;

        $mailer->setFrom($this->GetFromAddress(),"lkcmailer");   
		$mailer->From ='Admin';
        $mailer->Subject = 'Registration confirmation code';

        $confirmcode = $formvars['confirmcode'];
        	
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;
        
        $mailer->Body ="Hello ".$formvars['name']."\r\n\r\n".
        "Thanks for your registration with ".$this->sitename."\r\n".
        "Please click the link below to confirm your registration.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

	if (!$mailer->send()) {
    		echo "Mailer Error: " . $mailer->ErrorInfo;
	} else {
    		echo "Message sent!";
	}	
    }
	
	 
    function died($error) {
 
        // your error code can go here
 
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
 
        echo "These errors appear below.<br /><br />";
 
        echo $error."<br /><br />";
 
        echo "Please go back and fix these errors.<br /><br />";
 
        die();
 
    }
	

    function SendContactEmail()
    {
		require 'PHPMailerAutoload.php';
		$mailer = new PHPMailer;
		//$mailer->SMTPDebug = 2;
		//$mailer->Debugoutput = 'html';
        
        $mailer->CharSet = 'utf-8';
		$mailer->IsSMTP();
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port = 587;
		$mailer->SMTPSecure = 'tls';
		$mailer->SMTPAuth = TRUE;
		$mailer->Username = 'lkcmailer@gmail.com';  
		$mailer->Password = 'lkconsulting';
        $mailer->addReplyTo('lkcmailer@gmail.com', 'LK Consulting OÜ');  
        
        $mailer->AddAddress('anastassia.ivanova.94@gmail.com','Anastassia Ivanova');
        
        $mailer->Subject = "Teile on saabunud uus teade: ".$this->sitename;
        $sender =  $_POST['first_name'] + $_POST['first_name'];
        $mailer->setFrom($_POST['email'],$sender);   
	//$mailer->From =$_POST['first_name'];
        //$mailer->Subject = 'Saabunud kommentaar lehelt '.$this->sitename;
        
        $mailer->Body ="Tere,"."\r\n".
        "Teile on saabunud kommentaar lehelt ".$this->sitename."\r\n".
        $_POST['comments']."\r\n".
        "\r\n".
        "Parimat,\r\n".
        "LKCmailer\r\n".
        $this->sitename;

	if (!$mailer->send()) {
    		echo "Mailer Error: " . $mailer->ErrorInfo;
	} else {
    		echo "Message sent!";
		}	
    }


    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';

        $urldir ='';
        $pos = strrpos($_SERVER['REQUEST_URI'],'/');
        if(false !==$pos)
        {
            $urldir = substr($_SERVER['REQUEST_URI'],0,$pos);
        }

        $scriptFolder .= $_SERVER['HTTP_HOST'].$urldir;

        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "New registration: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="A new user registered at ".$this->sitename."\r\n".
        "Name: ".$formvars['name']."\r\n".
        "Email address: ".$formvars['email']."\r\n".
        "UserName: ".$formvars['username'];
		"phone_number: ".$formvars['phone_number'];
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    
    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }
        
	if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("This UserName is already used. Please try another username");
            return false;
        } 
              
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }
    
    // ---------- Save order to db code here.-------------

     function SaveOrderToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->EnsureOrderstable())
        {
            return false;
        }
        
        if(!$this->InsertOrderIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }

    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename where $fieldname='".$field_val."'";
        $result = mysqli_query($this->connection,$qry);   
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
    
    function DBLogin()
    {

        $this->connection = mysqli_connect($this->db_host,$this->username,$this->pwd, $this->database);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysqli_select_db($this->connection, $this->database))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysqli_query($this->connection,"SET NAMES 'UTF8'"))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        return true;
    }    
    
    function Ensuretable()
    {
        $result = mysqli_query($this->connection,"SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }
    
    function EnsureOrderstable()
    {
        $result = mysqli_query($this->connection,"SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTableOrders();
        }
        return true;
    }

    function CreateTable()
    {
       
    	$qry = "Create Table $this->tablename (".
                "id_user INT NOT NULL AUTO_INCREMENT ,".
                "name VARCHAR( 128 ) NOT NULL ,".
                "email VARCHAR( 64 ) NOT NULL ,".
                "phone_number VARCHAR( 16 ),".
                "username VARCHAR( 16 ),".
	         	"salt VARCHAR( 50 ),".
                "password VARCHAR( 80 ) NOT NULL ,".
                "confirmcode VARCHAR(32) ,".
                "PRIMARY KEY ( id_user ), ".
                "hybridauth_provider_name VARCHAR(255), ". 
	            "hybridauth_provider_uid VARCHAR(255))";
                "PRIMARY KEY ( id_user )".
                ")";
	
                
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
    

    function InsertIntoDB(&$formvars)
    {
    
        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);

        $formvars['confirmcode'] = $confirmcode;

	   $hash = $this->hashSSHA($formvars['password']);

	   $encrypted_password = $hash["encrypted"];     

	   $salt = $hash["salt"];            
 
        $insert_query = 'insert into '.$this->tablename.'(
		name,
		email,
		phone_number,
		username,	
		password,
		salt,
		confirmcode
		)
		values
		(
		"' . $this->SanitizeForSQL($formvars['name']) . '",
		"' . $this->SanitizeForSQL($formvars['email']) . '",
		"' . $this->SanitizeForSQL($formvars['phone_number']) . '",
		"' . $this->SanitizeForSQL($formvars['username']) . '",
		"' . $encrypted_password . '",
		"' . $salt . '",
		"' . $confirmcode . '"
		)';  

 
        if(!mysqli_query( $this->connection,$insert_query ))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
    }

    function UserID(){
        return isset($_SESSION['id_of_user'])?$_SESSION['id_of_user']:'none';
        
    }

    function InsertOrderIntoDB(&$formvars)

    {
         if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        } 
        
        $id_user = $_SESSION['id_of_user'];

        $insert_query = 'insert into orders (
        user_id,
        order_content
        )
        values
        (
        ' . $this->SanitizeForSQL($id_user) . ',
        "' . $this->SanitizeForSQL($formvars['order']) . '"
        )';  

 
        if(!mysqli_query( $this->connection,$insert_query ))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
    }

    #Hybriduth user generation and calling
    function mysqli_query_execute($sql)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   

	    $result = mysqli_query($this->connection, $sql );


	    if(!$result)
	    if(!$result )

	    {
		    die( printf( "Error: %s\n", mysqli_error($this->connection) ) );
	    }
        
	    return $result->fetch_object();
    }  
    function get_user_by_provider_and_id( $provider_name, $provider_user_id )
    {    
	    return $this->mysqli_query_execute( "SELECT * FROM users WHERE hybridauth_provider_name = '$provider_name' AND hybridauth_provider_uid = '$provider_user_id'" );     
    }
 
    function create_new_hybridauth_user( $email, $first_name, $last_name, $provider_name, $provider_user_id )
    {

	// let generate a random password for the user
	$password = md5( str_shuffle( "0123456789abcdefghijklmnoABCDEFGHIJ" ) );
	$insert_query ="INSERT INTO users(
    username,
    email, 
	password, 
	name, 
	hybridauth_provider_name, 
	hybridauth_provider_uid 
	) 
	VALUES(
    '$provider_user_id',
    '$email',
	'$password',
	'$first_name $last_name',
	'$provider_name',
	'$provider_user_id'
	)";
    $this->mysqli_query_execute($insert_query);
    }


    function GetAllOrders()
    
    {

        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        } 

        $sql_orders = "
            SELECT order_id, name, email, order_content 
            FROM orders 
            INNER JOIN users 
            ON orders.user_id = users.id_user";

        $result = mysqli_query($this->connection, $sql_orders);
              
        return $result;

    }

    function GetAllUserData()
    {
        
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        } 

        $sql_orders = "
            SELECT id_user, name, email  
            FROM users";

        $result = mysqli_query($this->connection, $sql_orders);
 
        return $result;

    }

    function GetOrderData()

    {

        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        } 
        
        $id_user = $_SESSION['id_of_user'];

        $sql_orders = "
            SELECT order_id, name, email, order_content 
            FROM orders 
            INNER JOIN users 
            ON orders.user_id = users.id_user
            WHERE user_id =". $id_user;
        
        $result = mysqli_query($this->connection, $sql_orders);
    
        return $result;

    }

    function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysqli_real_escape_string" ) )
        {
              $ret_str = mysqli_real_escape_string($this-> connection, $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
	
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
    
    /*BLOG stuff*/
	
	function InsertBlogPostToDB(){
	
	
	$table = "BLOG";
    mysqli_query ($this->connection,"CREATE TABLE IF NOT EXISTS '$table' (ID INT NOT NULL AUTO_INCREMENT, PRIMARY KEY ('id') ) ");
    mysqli_query ($this->connection,"ALTER TABLE '$table' ADO 'TITLE' TEXT NOT NULL");
    mysqli_query ($this->connection,"ALTER TABLE '$table' ADO 'SUMMARY' TEXT NOT NULL");
    mysqli_query ($this->connection,"ALTER TABLE '$table' ADO 'CONTENT' TEXT NOT NULL");
	
	$sql = "INSERT INTO $table SET
            TITLE = '$_POST(TITLE)',
            SUMMARY = '$_POST(SUMMARY)',
            CONTENT = '$_POST(CONTENT)'";
    $query = mysqli_query($this->connection,$sql);

    $select_sql = "SELECT * from $table";
    $result = mysqli_query($this->connection,$select_sql); while( $row = $mysqli_fetch_array($this->connection,$result));
   }
   
    function PostToBlog() 
	{
	$table = "BLOG";
	echo "<h1>$row(TITLE)</h1>";
    echo "<p><B>$row(SUMMARY)</B></p>";
    echo "<h1>$row(CONTENT)</h1>";
	}
	
  
	
}
?>
