# MuOnline-Sell-Resources
With this module you can easy sell resources from your inventory or warehouse. 
Hello everybody, I am releasing a module that was written by me these days called "Sell Resources". In first look it is a simple module, but actually it may do a good job on making all of your items useful. 

Proposition
Selling an items for credits via Web-shops sometimes frustrate players which doesn't like to donate. As you know they are many items that do not do anything in game, but making them harder to find and involving in selling for credits may get us to an interesting game-play and there we go. So let's make the game more interesting by giving a chance anyone to get some credits in alternative way than voting or donating.

Features
If you are wondering what is so interesting about this module, more than any other like this one out there, there is the list of the features that can be noticed.

   
 Simplicity: Easy for understanding configuration file
     Automatation: No need to touch anything into the module, only the config file
     Reliable Security: Form fields encryption and CSRF token] 
     Unencrypted code: Free to edit, share and use code 
     Dedicated: Can work separately from the website 
     All PHP version support: It does support all PHP versions up to 7.1.9
     Responsive design: Yes, it is done with bootstrap 4 
     All Seasons Support   Tested with Season 1, Season 9, Season 2 and it is working properly. Has not been tested with all seasons, but theoretically should work with them]]
         Sell directly from Inventory or Warehouse: You have the choice to select which way is suitable for you. ]
         Sell every item: You have the choice to sell any item that you wish and suits your server configurations. ]
         Every Resource With Different Cost: You have the choice to sell any item with different price than the others]
         Log All Messages: All messages from the module will be logged into /logs folder with the specific text, IP, date, username,character and request]


Installation

   
SQL Credentials: Simple configuration file on top as usual, nothing unknown. Just make sure to check "Default Instance"when you are installing SQLExpress as you may find difficulties to connect after.]
[PHP]
$option['sql_host']       = "r00tme-pc";          // Sql server host: 127.0.0.1,localhost,Your Computer Name, Instance
$option['sql_user']       = 'sa';                 // Sql server user: sa
$option['sql_pass']       = '12345';              // Sql server password
$option['sql_dbs']        = 'password';           // Mu online database: default = MuOnline

[/PHP]
     PHP Version: Make sure you type the proper config as other ways the module wont work. If your php version is 5.3 or below type 0, otherways type 1. 
If your PHP version is newer than 5.3 download the right SQLSRV driver/extension for your ph version and enable the extension from php.ini (php_sqlsrv_ts.dll). ]
[PHP]
$option['php_5.3+']       = 1;                    // PHP version switch, 0 = using mssql_query, 1= using sqlsrv_query 
[/PHP]
     Seasons: Important to change when the Season is 1 or below to 0 and whenever is newer than Season 1 to 1.] 
[PHP]
$option['mu_version']     = 1;                    // 0 - Season 1(97-99), 1 = Season 2+ up to 12
[/PHP]
     Web Session: In all cases whether you use the module separated or implemented to the website, you have to replace the "Drakon" with your website session. Example: $option['web_session']    = $_SESSION['username']; ]
[PHP]
$option['web_session']    = "Drakon";             // Web Session
[/PHP]
     Inventory/Warehouse: As explained the module may work with inventory or warehouse depending on the configurations. So  if 0 is set all items will be sold from inventory and 1 from warehouse ]
[PHP]
$option['invent_ware']    = 0;                    // 0 - Inventory Only, 1= Warehouse Only 
[/PHP]
     Credit Table: As you know many websites uses different credit tables and columns, so I have decided to put this on the main config and can be easily configure]
[PHP]
$option['credits_tbl']    = "memb_credits";       // Credits Table
$option['credits_col']    = "credits";            // Credits Column
$option['credits_usr']    = "memb___id";          // Credits User
[/PHP]
     Credit Price: Every single resource for selling has a specific price which needs to be configured here. The first item in the form field is the first number here. Please make sure that you have a same number of prices as items for sell, not less because the module wont work properly other ways.]]
[PHP]
$option['exhange']        = array(5,12,150);      // Credits per resource, make sure you have exact total numbers as resources for exchange 
[/PHP]
     Items for Sell   Make sure you type a proper Season Hex here as other ways the module wont work. Check out the pictures for easy understanding how to deal with it.]]
[PHP]
$option['res']            = array                 // Resource / Resource Name 
       (  
// Stone Season 1 Code: D508  | Stone Season 2+ Code: 1508, so make sure you are typing a valid hex codes related to your MuOnline Server	   
        "1508" => "stone",                        
	"1500" => "rena", 
        "1A20" => "Box of Treasure"		
		);                                        

[/PHP]
Season up to 1
https://image.prntscr.com/image/1uIZikxKRCKb-xE0miA2gw.png
Season 2-12
https://image.prntscr.com/image/6gp9ZgK4Q86FUTSX7ccZ1A.png

         Form Fields Key: This is your security key that will be used for the post data encryption and csrf token protection. Please note that the different PHP versions uses different encrypting functions and you must make sure that the current one extension is enabled in the php.ini to make it work. Check out the pictures below to see the differences. Be aware that openssl encryption function will be deprecated in PHP version 7.2, so future updates will be needed to support it.]
[PHP]
$option['enc_key']        = "@r00tme";           // Form Fields Encryption / PHP up to 5.3 uses extension mcrypt / PHP up to 7.1.9 uses openssl_random_pseudo_bytes, so make sure they are uncommented in php.ini
[/PHP]
https://image.prntscr.com/image/Av8yWMLbT__-S1P_ZSfSeg.png
https://image.prntscr.com/image/sI8BscfCS1mt2ITYwbE5OA.png


So that is all about the module, hopefully you will find it useful and manage to install/configure (installing is hard to be called :D) it by yourself. 


