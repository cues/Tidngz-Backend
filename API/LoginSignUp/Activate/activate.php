<!DOCTYPE html>
<?php

// require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data.php"; 


?>

<html>
<head>
<title>Tidngz Activation</title>
</head>
<body style='background-color:rgba(177,177,177,1)'>

    <?php

if( !empty($_GET['code']) )
{

        $email        =    empty($_GET['email'])      ?    ''    :    mysqli_real_escape_string($con,$_GET['email']);
        $code         =    empty($_GET['code'])     ?    ''    :    mysqli_real_escape_string($con,$_GET['code']);
        
        $sanitize_4 = array($email, $code);


        if( Sanitize::check_sanitize($sanitize_4, 4) ){
             response(400,"Invalid Request",NULL);
             exit();
        }
           

        $activate = New Activate();
        $data = $activate->activate_account($email, $code);




    if(empty($data))
    {
        response(200,"Unsuccess",NULL);
        exit();
    }
    else
    {
        response(200,"Success",$data);
        exit();
    }
   
	
}
else
{
	response(400,"Invalid Request",NULL);
    exit();
}


?>
</body>
</html>




