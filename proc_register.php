<?
include "./lib/mysql.php";

$userid="jinny17";
$password="wlsgml17";
$salt='SALTIDPWD';
$userpwd=md5($salt.md5($password.$salt));
$query = "INSERT INTO member VALUES('$userid', '$userpwd', 'ultra', '', '', 1, '20170201', '20190416', '', '', '서울시 용산구', 'test@test.com', '', '', 0, 0, NULL);";
$result = mysqli_query($connect, $query);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

?>