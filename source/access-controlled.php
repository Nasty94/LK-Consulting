<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv="Content type" content="text/html; charset=ISO-8859-1">
	<meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
     
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>   
	
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" type="text/css">
	<link rel="stylesheet" href="style/menubar.css">
	<link rel="stylesheet" href="style/style.css">
	<link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
        <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
<div id="left"></div>
		  <div id="right"></div>
		  <div id="top"></div>
		  <div id="bottom"></div>
		  <div id="english">

		  
		  <div id="img">
     		 
		  <img src="img/LK.jpg" width=auto height=auto>
		  </div <!-- img -->

<div class="dropdownmenu">
        <ul id="nav">
            <li class='active'><a href='index.html'>Avaleht</a></li>
            <li><a href="#">Minu konto</a>
                <div>
                    <ul>
                        <li><a href='register.php'>Uus kasutaja</a></li>
                        <li><a href='login.php'>Logi sisse</a></li>
                       
                    </ul>
                </div>
            </li>
			<li><a href="#">Meist</a>
                <div>
                    <ul>
                        <li><a href="staff.html">Personal</a></li>
                        <li><a href="company.html">Ettevõtes</a></li>
  
                    </ul>
                </div>
            </li>
			<li><a href="#">Teenused</a>
                <div>
                    <ul>
                          <li><a href="./web/services/data_analysis.html">Andmeanalüüs ja töötlus</a></li>
						<li><a href="./web/services/stat_modelling.html">Statistiline modelleerimine</a></li>
					    <li><a href="./web/services/med_stat.html">Meditsiinistatistika</a></li>
                        <li><a href="./web/services/market_research.html">Turuuring</a></li>
                        <li><a href="./web/services/finance.html">Finantsaruannete analüüs</a></li>
					    <li><a href="./web/services/consultations.html">Konsultatsioonid</a></li>
  
                    </ul>
                </div>
            </li>
            
            <li><a href="#">Blog</a>
                <div>
                    <ul>
                        <li><a href="web/articles/article_sample.php">Näidis</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                        <li><a href="#">Page 4</a></li>
                        <li><a href="#">Page 5</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="contact.php">  Kontakt  </a></li>
	    <li><a href="tech_support.html">    Abi    </a></li>
			
            <li class="pad"></li>
        </ul>
    </div>
	<div id="main">
	<div id="white-box" >
	 <div id="contentInt">
                 <noscript>
                        <p class="note">You have disabled Javascript. This website will not function without it.</p>
                 </noscript>
                      <h1></h1>


			   		<div class="center">
		
<div id='fg_membersite_content'>
<h2>This is an Access Controlled Page</h2>
This page can be accessed after logging in only. To make more access controlled pages, 
copy paste the code between &lt;?php and ?&gt; to the page and name the page to be php.
<p>
Logged in as: <?= $fgmembersite->UserFullName() ?>
</p>
<p>
<a href='login-home.php'>Home</a>
</p>
</div>
</div>
</div> <!--center-->


		      </div> <!--contentInt-->
		   		   

             
	</div> <!-- white box --> 
	</div> <!-- main -->

		  
	<div id="footer">
                      © 2015  LK Consulting <br>
					  This is a proof-of-concept web application.
	</div>
</body>
</html>
