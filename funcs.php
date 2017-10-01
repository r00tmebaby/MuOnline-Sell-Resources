<?php
session_start();

function csrf_token(){
if (!isset($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }
}

function encrypt($data){
	require("config.php");
	if(function_exists('openssl_random_pseudo_bytes')){
        $encryption_key = base64_decode( $option['enc_key']);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
	}
	else{
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$option['enc_key'],$data,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"));
	}
}

function decrypt($data){
	require("config.php"); 
    if(function_exists('openssl_random_pseudo_bytes')){	
        $encryption_key = base64_decode($option['enc_key']);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
	else{
	  $decode = base64_decode($data);return mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$option['enc_key'],$decode,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");    
	}
}

function hash_equal($str1, $str2)
{
    if(strlen($str1) != strlen($str2))
    {
        return false;
    }
    else
    {
        $res = $str1 ^ $str2;
        $ret = 0;
        for($i = strlen($res) - 1; $i >= 0; $i--)
        {
            $ret |= ord($res[$i]);
        }
        return !$ret;
    }
}
  

function countr($char,$amount=0,$run=false){
  require("config.php");
  if (isset($_POST['token']) && isset($_SESSION['token']) && isset($_POST['res'])) {
    if (hash_equal($_SESSION['token'], $_POST['token'])) {
     $broi    = 0;
     $go      = 0;
     $info    = $amount;
     $count   = array();
     $keys    = array_keys($option['res']);
	 $val     = array_values($option['res']);
     $res     = (int)$_POST['res'];
     switch($option['mu_version']){
		 case 1:  
		        if($option['invent_ware'] == 0){
			     	$lenght=1728; $column = "Inventory";$table="Character";$where = "Name"; $type=$char;
			     }
			     elseif($option['invent_ware'] == 1){
			     	$lenght=1920; $column = "Items";$table="Warehouse";$where = "AccountID"; $type=$option['web_session'];
			     }
			$hex=32;
	 break;		 
		 default: 		 
		        if($option['invent_ware'] == 0){
			     	$lenght=760; $column = "Inventory";$table="Character";$where = "Name"; $type=$char;
			     }
			     elseif($option['invent_ware'] == 1){
			     	$lenght=1200; $column = "Items";$table="Warehouse";$where = "AccountID"; $type=$option['web_session'];
			     }			 
		    $hex=20;			  
	 break;
    }
		 
		if(empty($option['exhange'][$res]) ){
			   die ("<div class='alert alert-danger text-center' role='alert' ><b>Please configure a credits amount for selling in the config first!!!</div>");
		}
      for($i=0;$i<120;$i++){
		        if($option['php_5.3+'] == 1){
		          $items = mssql_fetch_array(mssql_query("SELECT [".$column."] FROM [".$table."] WHERE [".$where."]='".$type."'"));
				  $items = strtoupper(bin2hex($items[$column]));
		        }
		        else{
		          $items = substr(mssql_get_last_message(mssql_query("declare @it varbinary(".$lenght."); set @it=(select [".$column."] from [".$table."] where [".$where."]='".$type."'); print @it")),2);	        	
		        }
    				if(substr($items,$i*$hex,4) == $keys[$res] && $run == false){ 
    				$count[] = $broi++; 
    		        }				
                   if(substr($items,$i*$hex,4) == $keys[$res] && $amount > 0 && $run == true){
					 
    				 $go++;
                     $invent   = substr_replace($items,str_repeat("F",$hex),$i*$hex,$hex);   
                     $upd_inv  = mssql_query("Update [".$table."] set [".$column."] =0x".$invent." where [".$where."] ='".$type."'");					 
    				  --$amount;
    				    if($go == 1){
    						if(((bool)$upd_inv === true)){								
    						$upd_cr   = mssql_query("Update [".$option['credits_tbl']."] set [".$option['credits_col']."] = [".$option['credits_col']."] + ".bcmul($info,$option['exhange'][$res])." where [".$option['credits_usr']."]='".$option['web_session']."'");
    						echo "<div class='alert alert-success text-center' role='alert' >You have successfully exhanged ".$info." ".$val[$res]." for ".bcmul($info,$option['exhange'][$res])." credits</div>";				
    					     make_log("Success Log"," Table: ".$table. "| Exchanged: " .$info." ".$val[$res]." for ".bcmul($info,$option['exhange'][$res])." credits | Account: ". $option['web_session'] . " | Character: " .$type . " | IP: ".ip());
						   }
    					  else{
							 make_log("Error Log"," Item Update Query Returns False | Table: ".$table. "| Exchanged: " .$info." ".$val[$res]." for ".bcmul($info,$option['exhange'][$res])." credits | Account: ". $option['web_session'] . " | Character: " .$type . " | IP: ".ip());
    						echo "<div class='alert alert-warning text-center' role='alert' >Something went wrong, please contact the administrator</div>";
    					   }
    					 }
    				   }			   
                   }								
       return array(count($count));
    	}
    }
}

function ip() {
    $ipaddress = '0.0.0.0';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function make_log($file_name, $content)
{
	$file_date = date('d_m_Y', time());
    $log_date = date('h:i:s', time());
	$log_content='Date: '.$log_date .' | ' . $content . "\r\n";
	file_put_contents('logs/'.$file_name.'['.$file_date.'].log', $log_content, FILE_APPEND);
}

function protect($var=NULL) {
$newvar = @preg_replace('/[^a-zA-Z0-9\_\-\.]/', '', $var);
if (@preg_match('/[^a-zA-Z0-9\_\-\.]/', $var)) { }
return $newvar;
}

function exists($acc,$char=false){
	$chara ='';
		$accs   = mssql_query("Select * from Character where AccountID = '".$acc."'");
	    while($chars = mssql_fetch_array($accs)){
	      $chara .= "<option value=".encrypt($chars['Name']).">".$chars['Name']."</option>";
        }	  	
    if($char <> false){
	 $charsa = protect($char);
	  if(mssql_num_rows(mssql_query("Select * from Character where accountId ='".$acc."' and Name='".$charsa."'")) == 1){
	    return true;
	  }
   }
   return $chara;
}





?>