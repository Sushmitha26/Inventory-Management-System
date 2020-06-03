<?php

include_once("./database/constants.php");
if(isset($_SESSION["userid"])) {
	session_destroy();
}
header("location:".DOMAIN."/");  //if logged out,the page will be directed to login page

?>