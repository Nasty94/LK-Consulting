<?php
require('../../web/comment-sys/Persistence.php');
$comment_post_ID = 1;
$db = new Persistence();
$comments = $db->get_comments($comment_post_ID);
$has_comments = (count($comments) > 0);

?>


<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minu konto</title>
     
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>  
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
	
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type='text/javascript' src='../../scripts/gen_validatorv31.js'></script>
    <script src="../../scripts/pwdwidget.js" type="text/javascript"></script>   
    <script src="../../js/disable.js"></script>
	
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" type="text/css">
	<link rel="stylesheet" href="../../style/menubar_test.css">
	<link rel="stylesheet" href="../../style/style_test.css">
	<link rel="STYLESHEET" type="text/css" href="../../style/pwdwidget.css" />
	<link rel="STYLESHEET" type="text/css" href="../../style/comment-main.css" />
    <link rel="STYLESHEET" type="text/css" href="../../style/fg_membersite_test.css" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../style/bootstrap.min.css">


        
    	
</head>

<body>
    
        <div class="container-fluid text-center">
          <div class="row">
             <div class="col-md-8 col-md-offset-2">
                    <div id="english">
		            <a id="eng" href="web/lang/eng/index_eng.html">ENG</a>&nbsp;&nbsp;
	
		  </div>
             </div>
           </div>
           <div class="row">
            <div class="col-md-8 col-md-offset-2">
		        <div id="img">
                    <img src="../../img/LK.jpg" class="img-responsive" alt="img/LK.jpg"/>
		        </div> 
            </div>
           </div>
          <div class="row">
            <div class="col-md-8 col-md-offset-2">

            <div class="dropdownmenu">
            <ul id="nav">
            <li class='active'><a href='../../index.html'>Avaleht</a></li>
            <li><a href="../../login-home.php">Minu konto</a>
                <div>
                    <ul>
                        <li><a href='../../register.php'>Uus kasutaja</a></li>
                        <li><a href='../../login.php'>Logi sisse</a></li>
                       
                    </ul>
                </div>
            </li>
			<li><a href="#">Meist</a>
                <div>
                    <ul>
                        <li><a href="../../staff.html">Personal</a></li>
                        <li><a href="../../company.html">Concepts</a></li>
  
                    </ul>
                </div>
            </li>
			<li><a href="#">Teenused</a>
                <div>
                    <ul>
                        <li><a href="#">Turuuring</a></li>
                        <li><a href="#">Med. statistika</a></li>
  
                    </ul>
                </div>
            </li>
            
            <li><a href="#">Blog</a>
                <div>
                    <ul>
                        <li><a href="./web/articles/article_sample.php">Näidis</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                        <li><a href="#">Page 4</a></li>
                        <li><a href="#">Page 5</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="../../contact.php">  Kontakt  </a></li>
			<li><a href="#">           </a></li>
            <li class="pad"></li>
        </ul>
          <div class="dropdown">
            <button class="btn btn-success" type="button" id="menu1" data-toggle="dropdown">Menüü
            <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
              <li role="presentation"><a role="menuitem" tabindex="-1" href="../../index.html">Avaleht</a></li>
                <li role="presentation" class="divider"></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href='../../login'>Logi sisse</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href='../../register.php'>Uus kasutaja</a></li>
                <li role="presentation" class="divider"></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Meist</a></li>
                <li role="presentation" class="divider"></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Turu uuring</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Med statistika</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="article_sample.php">Blogi</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="contact.php">Kontakt</a></li>
              
              
            </ul>
          </div>
    </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          	<div id="main">
	            <div id="white-box" >
	                        <div id="contentInt">
                            <noscript>
                                <p class="note">You have disabled Javascript. This website will not function without it.</p>
                            </noscript>



			   		        <div class="center">
                                   	  <div class="textart">
	  <article class="hentry">	
			<header>
				<h2 class="entry-title"><a href="#" rel="bookmark" title="Permalink to this What You Need from your manager to be successful">What You Need from your manager to be successful</a></h2>
			</header>
			
			<footer class="post-info">
				<abbr class="published" title="2012-02-10T14:07:00-07:00">
					25th February 2015
				</abbr>

				<address class="vcard author">
					By <a class="url fn" href="#">LisBeth</a>
				</address>
			</footer>
			
			<div class="entry-content">
				<p>What You Need from your manager to be successful!

In April I participated in the ‘Retrospective Facilitators Gathering 2013’ in North Carolina, USA, also known as the ‘RFG’.  RFG was started by Esther Derby (esther derby associates, inc.), Diana Larsen (FutureWorks Consulting, LLC) and Norm Kerth (Elite Systems) in 2002.

RFG is based on Open Space principles where lightweight planning and a market place for different sessions are the main points – a self-organising system. The elements or parts that need planning are planed i.e. the venue, supplies etc. Those aspects that don’t need close planning, like the sessions, develop organically during the week. Each year, different volunteers organize the RFG, and the venue is altered between the US and Europe. So the RFG 2014 will be in Hungary.

I participated in many interesting and insightful sessions during the RFG 2013 week. I would like to share, one session, which I think is valuable for a lot of organisations in understanding how managerial behaviour influences the performance of their people.

The session was called ‘What You Need from your manager to be successful!’ and was hosted by Susan DiFabio (SKD Consulting).

Highly motivated employees are between 52% and 127% more efficient than the average motivated employees. As a leader it is therefore worthwhile to focus on how to create a motivating work environment. There are several elements that are part of the motivating work environment, but the most important elements are Commitment, Respect and Trust.
<br>
Commitment
<br>
As a leader you must ensure that your employees are engaged through knowledge of what creates their commitment. Different people respond to different measures. To become extremely engaged some employee’s need you set up goals with them. On the other hand, others must feel safe and secure to be committed to their work.
<br>

Respect and trust
<br>
As a leader you can ensure respect by inspiring and contributing to open communication. A work environment where there is room to discuss different views and to admit mistakes. This creates mutual trust and the chance to learn from each other. Conversely many secrets and taboos typically lead to people being suspicious of each other and to sub-optimise within their own areas.
<br>
What You Need (WYN) from your manager to be successful!
<br>
For you as a leader to understand what your employees need from you to be successful, and for you to know, how you in the best way can provide an environment that enables success, I would suggest you to try WYN – What you need from your manager to be successful!
<br>
Why?
<br>
  -  For managers to understand how their behavior influences and affects their team members and their performance.<br>
  -  Because managers do not always know, what their team members think about their behavior.<br>
  -  To improve transparency and open communication.<br>
  -  To improve or continue to develop commitment, respect and trust within the team.<br>
  -  To receive constructive and appropriate feedback from team members.<br>

Content?<br>

    - Workshop based sessions with active participation from both manager and team members.<br>
    - 2 workshops of 1½ hour on 2 consecutive days.<br>
       - Workshop 1 – Data gathering – team members only.<br>
       - Workshop 2 – Feedback and planning– team members and manager.<br>
       - Follow-up workshop 4-5 months later.<br>

What will you gain?<br>

   - An open communication about how individual behavior can influence the daily performance within the team.<br>
   - Possibility to improve team culture and trust level, which will influence the performance of the team.<br>
   - Understand what contributes to a good team performance.<br>
   - Identifying what could be done better.<br>
   - Setting up a plan for what each individual should work with to improve the team performance.<br>
</p>
			</div>
		</article>

	</div>

			


	<div class="center"> 

      	<section id="comments" class="body">

        <form name="subform" id="FORM">
                    <br>                
	            
	 <header>
		<h2>Tagasiside</h2>
	</header>

    <ol id="posts-list" class="hfeed<?php echo($has_comments?' has-comments':''); ?>">
      <li class="no-comments">Jaga oma arvamust!</li>
      <?php
        foreach ($comments as &$comment) {
          ?>
          <li><article id="comment_<?php echo($comment['id']); ?>" class="hentry">	
    				<footer class="post-info">
    					<abbr class="published" title="<?php echo($comment['date']); ?>">
    						<?php echo( date('d F Y', strtotime($comment['date']) ) ); ?>
    					</abbr>

    					<address class="vcard author">
    						By <a class="url fn" href="#"><?php echo($comment['comment_author']); ?></a>
    					</address>
    				</footer>

    				<div class="entry-content">
    					<p><?php echo($comment['comment']); ?></p>
    				</div>
    			</article></li>
        
          <?php
        }
      ?>
		</ol>
        </form>
		 <a href="javascript:enable();" >Vaata kommentaare</a> 
		<div id="respond">

      <h3>Mida Sina arvad artiklist?</h3>

      <form action="../comment-sys/post_comment.php" method="post" id="commentform">

        <label for="comment_author" class="required">Nimi</label>
        <input type="text" name="comment_author" id="comment_author" value="" tabindex="1" required="required">
        
        <label for="email" class="required">Email</label>
        <input type="email" name="email" id="email" value="" tabindex="2" required="required">

        <label for="comment" class="required">Sinu arvamus</label>
        <textarea name="comment" id="comment" rows="10" tabindex="4"  required="required"></textarea>

        <input type="hidden" name="comment_post_ID" value="<?php echo($comment_post_ID); ?>" id="comment_post_ID" />
        <input name="submit" type="submit" value="Submit"/>
        
      </form>
    
    </div>
			
	</section>
	        </div>
	    </div>
        </div>
        </div>
   </div>
    </div>
     

		
          
   </div> <!-- white box -->
   <br><br><br>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">		  
	        <div id="footer" >
                      © 2015  LK Consulting <br>
					  This is a proof-of-concept web application.
	        </div>
        </div>
    </div>


    </div>
        
    </body>
</html>
