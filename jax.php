<?php
require("config.php");
require("funcs.php");

switch(trim($_GET['f'])){

    case 'chars' :
	  if($option['invent_ware'] == 0){
	   if(isset($_POST['char']) && isset($_POST['res'])){
		   $broi  = (int)$_POST['broi'];
		   $keys  = array_values($option['res']);
           $res   = (int)$_POST['res'];
		   $char  = protect(decrypt($_POST['char']));
		   $data  = countr($char);	

		   if(empty($option['exhange'][$res]) ){
			   die ("<div class='alert alert-danger text-center' role='alert' ><b>Please configure a credits amount for selling ".$keys[$res]." in the config first!!!</div>");
		   }
		
            if((bool)exists($option['web_session'],$char) == true){
		        if($data[0] > 0){
					if(empty($broi)){
                      echo "<div class='alert alert-info text-center' role='alert' ><b>".$char."</b> has  ".$data[0]."\n". $keys[$res]." now, you can exchange them for ".number_format(bcmul($data[0],$option['exhange'][$res]))." credits</div>";
                   }
				   else{
					   if($data[0] >= $broi){
					     echo "<div class='alert alert-info text-center' role='alert' ><b>".$char."</b> has  selected ".$broi."\n". $keys[$res]." and they can be exchanged  for ".number_format(bcmul($broi,$option['exhange'][$res]))." credits</div>";
				      }
					  else{
						echo "<div class='alert alert-danger text-center' role='alert' ><b>".$char."</b> has  selected ".$broi."\n". $keys[$res]." but doesn't have that much into the inventory</div>";  
					  }
				   }
				}
		        else{
		      	   echo "<div class='alert alert-danger text-center' role='alert' ><b>".$char."</b> doesn't have any ".$keys[$res]." at the moment</div>";
		        }
			}
	    }
	  }
    elseif($option['invent_ware'] == 1){
		if(isset($_POST['res'])){
		   $broi  = (int)$_POST['broi'];
		   $keys  = array_values($option['res']);
           $res   = (int)$_POST['res'];
		   $data  = countr($option['web_session']);
		   if(empty($broi)){
			   echo "<div class='alert alert-info text-center' role='alert' ><b>".$option['web_session']."</b> has  ".$data[0]."\n". $keys[$res]." now, you can exchange them for ".number_format(bcmul($data[0],$option['exhange'][$res]))." credits</div>";
		   }
		}  
    }
	
break;
			
}   
?>