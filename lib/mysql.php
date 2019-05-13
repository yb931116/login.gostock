<?

$connect = mysqli_connect("localhost", "test2", "1234","gostock_ssm");

if(!$connect){
	die("Connect Error[".mysqli_connect_errno()."]:".mysqli_connect_error());
}

$dbselect = mysqli_select_db($connect, "gostock_ssm");

if(!$dbselect)
{
	mysqli_close($connect);
	die("DBSelect Error[".mysqli_connect_errno()."]:".mysqli_connect_error());
}

?>