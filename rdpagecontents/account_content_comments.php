<?php
if(!isset($_GET['transmitter']) || ($_GET['transmitter']!='account.php') ){}
else
{
	session_start(); //start session
	session_regenerate_id(true); //regenerate session id
	//regenerate session id if PHP version is lower thatn 5.1.0
	if(!version_compare(phpversion(),"5.1.0",">=")){ setcookie( session_name(), session_id(), ini_get("session.cookie_lifetime"), "/" );}

	unset($_GET['transmitter']);
?>
<div>

<?
	print_r($_SESSION['user']);
?>

</div>
<? } ?>