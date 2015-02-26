inf5

Anastassia Ivanova, Markus Lippus, Annett Saarik

---------------------------------------------------------------------------------------------------------------------

Test environment:

azure

http://lk-consulting.azurewebsites.net/source/

---------------------------------------------------------------------------------------------------------------------

NB!  During development was used wamp server.

---------------------------------------------------------------------------------------------------------------------


# Simple Registration/Login code in PHP


## Installation

1. Edit the file `membersite_config.php` in the includes folder and update the configuration information (like your email address, Database login etc)
    **Note**
    The script will create the table in the database when you submit the registration form the first time. 

2. Upload the entire 'source' folder  to your web site. 
    
3. You can customize the forms and scripts as required.




## Files

* register.php 

    This script displays the registration form. When the user submits the form,
the script sends a confirmation email to the user. The registration is complete only when
the user clicks the confirmation link that they received in the email

* confirmreg.php

    Confirms a user's email address. The user clicks the confirmation link that they receive at their email address and is send to this script. This script verifies the user and  marks the user as confirmed. The user can login only after he has confirmed himself.

* login.php

    The user can login through this login page. After successful login, the user is sent to the page login-home.php
    
* access-controlled.php

    This is a sample accesscontrolled page. If the user is logged in, he can view this page. Else the user is 
sent to login.php
    
* includes/membersite_config.php
    Update your confirguration information in this file
    
* includes/fg_membersite.php

    This file contains the main class that controls all the operations (validations, database updation, emailing etc)
If you want to edit the email message or make changes to the logic, edit this file
    
* includes/class.phpmailer.php

    This script uses PHPMailer to send emails. 
    
* includes/formvalidator.php    

    For form validations on the server side, the PHP form validator from HTML form guide is used.
    
## Real time comment system

* Style: css/

* Main form: arcticle_sample.php (each article pae will contain comment system)

## Files

* clear.php

* post_comment.php

* Persistance.php

* txt file

