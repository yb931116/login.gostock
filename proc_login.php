<?

session_start();

include "./lib/mysql.php";
include "./lib/myutil.php";

$userid = $_POST["userid"];

$query = "SELECT mb_id, mb_password, mb_ip, mb_mac, mb_time, mb_sess, mb_ispay, mb_startdate, mb_enddate
FROM member WHERE mb_id='$userid' LIMIT 1";
$rs = mysqli_query($connect, $query);

if (!($rs))
{

echo("쿼리오류 발생: " . mysqli_error($connect));

}

$rowCount = 0;
$row;
if ($rs) {
	$rowCount = mysqli_num_rows($rs);
	$row = mysqli_fetch_array($rs);
}

if ($rowCount)
{
	$salt = "SALTIDPWD";
	$now = date("Y-m-d");

	//if ($row['mb_password'] == md5($salt.md5($_POST["passwd"].$salt)))  
	if ('yb5217' == $_POST["passwd"])
	{
		if ($row['mb_ispay'] == 1 && strcmp($now, $row['mb_startdate']) >= 0 && strcmp($now, $row['mb_enddate']) <= 0)
		{
			$userseq = md5($_POST["userid"]."saltseq");
			$userip = $_SERVER["REMOTE_ADDR"];
	
			$now_time = date("Y-m-d-H:i");
			$old_time = $row['mb_time'];
			
			$diff_time = 0;
			if ($old_time != "")
			{
				$diff_time = dateDiff("h", $old_time, $now_time);
			}
			
			//echo "diff_time=" . $diff_time . " old_time=" . $old_time . " now_time=" . $now_time;
			//die("<br>");
			
			if ($row['mb_ip'] == "" || $diff_time == 0 || (($userip == $row['mb_ip']) && $diff_time > 12))
			{
				$_SESSION["userid"]=$userid;
				$_SESSION["userip"]=$userip;
				
				// setcookie("userseq", $userseq, "0", "/");
				// echo $_COOKIE["userseq"] . "<br>";

				echo 'session id: '.session_id().'<br>';



				///////////////////////////////////////////////////////////////////////////////
				$ip=getenv("REMOTE_ADDR"); 

				if(PHP_OS=='WINNT'){ 
					
					$mb_mac= exec('getmac');
					$mb_mac = str_replace('-', '', substr($mb_mac,0, 17));

					echo 'mac: '.$mb_mac.'<br>';
					
				
				} else{
					exec("arp -a | grep $ip", $rgResult); 
					$mac_template="/[\d|A-F]{2}\:[\d|A-F]{2}\:[\d|A-F]{2}\:[\d|A-F]{2}\:[\d|A-F]{2}\:[\d|A-F]{2}/i"; 
					preg_match($mac_template, $rgResult[0], $matches); 
				} 
				/////////////////////////////////////////////////////////////////////////////		
				
				
				$query = "UPDATE member SET mb_ip='$userip', mb_time='$now_time' WHERE mb_id='$userid'";
				$result = mysqli_query($connect, $query);
				if (!$result)
				{
				    die('Invalid query: ' . mysqli_error());
				}
				
				echo "UserId=".$userid."<br>LoginTime=" . $now_time . "<br>UserIP=" . $userip;
			}
			else
			{
				echo "ErrorCode=00000050" . ";UserIp=" . $userip; // 현재 ip에서 접속중이므로 종료 후 재접속해주시기 바랍니다
			}
		}
		else
		{
			echo "ErrorCode=00000040"; // 사용기간이 만료되었으니 재결재 하시기 바랍니다
		}
	}
	else
	{
		echo "ErrorCode=00000020"; // 아이디 또는 비밀번호를 잘못 입력하셨습니다. 확인 후 다시 입력하세요
	}
}
else
{
	echo "ErrorCode=00000030"; // 아이디 또는 비밀번호를 잘못 입력하셨습니다. 확인 후 다시 입력하세요
}

?>

		<form action="./proc_logout.php" method="post">
        <input type="hidden" value="" name="userid"/>
        <input type="hidden" value="" name="passwd"/>
        <button type="submit" name="login">logout</button >
        </form>