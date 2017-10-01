<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<?php

require("config.php");
require("funcs.php");
csrf_token();
$push     = '';

$messages = array();
$cr       = mssql_fetch_array(mssql_query("Select credits from [".$option['credits_tbl']."] where [".$option['credits_usr']."]='".$option['web_session']."'"));

    if($cr[$option['credits_col']] === NULL){
       	mssql_query("Insert into [".$option['credits_tbl']."] (".$option['credits_col'].",".$option['credits_usr'].") values (0,'".$option['web_session']."')");
    		header("Refresh:0");
    }
	
if($option['invent_ware'] == 0) { 
	$push = '   <div class="input-group-addon">Select Character</div>			   
                 <select class="form-control mb-2 mr-sm-2 mb-sm-0" onclick="functions(\'chars\')" name="char">
				       <option disabled selected></option>
        			   '.exists($option['web_session']).'				  
        		 </select>';  
	if(isset($_POST['broi']) && isset($_POST['char'])){
	 $char  = protect(decrypt($_POST['char']));
	  if((bool)exists($option['web_session'],$char) <> false){
	    $broi  = (int)$_POST['broi'];
		$data  = countr($char);
		if($broi > 0 && $broi <= $data[0]){	
                countr($char,$broi,true);
			    }
		   else{
			   echo "<div class='alert alert-danger text-center' role='alert' >You do not have these resources</div>";
		   }
    }
	else{
		echo "<div class='alert alert-danger text-center' role='alert' >This character doesn't exist</div>";
	}
  }
}

elseif($option['invent_ware'] == 1){
	$push  = "";
	if(isset($_POST['broi']) && isset($option['web_session'])){
	$broi  = (int)$_POST['broi'];
	$data  = countr($option['web_session']);
		if($broi > 0 && $broi <= $data[0]){	
                countr($option['web_session'],$broi,true);
			}
		else{
			   echo "<div class='alert alert-danger text-center' role='alert' >".$option['web_session']." doesn't  have these resources</div>";
		}
	}
}


echo '
    <div class="container col-lg-12">
     <div class="container col-md-5"> 	 
		 <div id="charsm"></div> 
		 <ul class="list-group">
		 	<li class="list-group-item justify-content-between">
               Current account
             <span style="font-size:12pt" class="badge badge-default badge-pill" id="credits">'.$option['web_session'].' </span>
           </li>
		 	<li class="list-group-item justify-content-between">
               Current credits
             <span style="font-size:12pt" class="badge badge-default badge-pill" id="credits">'.$cr[$option['credits_col']].' </span>
           </li>		

	 </div>
	 <div style="margin-bottom:20px;"></div>
      <div class="container col-md-3">
            <form  id="chars" method ="post">	
               <div class="form-group">	
                '.$push.'
				<div class="input-group-addon">Select Resource</div>			   
                 <select class="form-control mb-2 mr-sm-2 mb-sm-0" name="res">
				    ';
					   $ni = -1;
					   foreach($option['res'] as $res=>$value){
						   $ni++;
						   echo "<option value='".$ni."'>".$value."</option>";
					   }
                  echo'					   
        		 </select>
				<input type="hidden" name="token" value="'.$_SESSION['token'].'"/>
				 <div class="input-group-addon">Type Amount To Sell</div>
                 <input class="form-control"  onclick="functions(\'chars\')" type="number"  name="broi"/>       		 
				 <input class="form-control mb-2 mr-sm-2 mb-sm-0 btn btn-primary" type="submit"  value="Sell Stones"/>
            </form>
		</div>
	</div>  
</div>
	
';

?>

<script>
function functions(func){
    var str = $('#'+func).serialize();
    $.ajax({
        url: 'jax.php?f='+func,
        type: "POST",
        data: ''+str+'',
        success: function(msgc)
        {
            $('#'+func+'m').empty().append(""+msgc+"").hide().fadeIn("fast");
        }
    });
	
}
</script>