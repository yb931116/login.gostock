<?

function loginchk($userid, $userseq, $userip)
{
	if (!$userid or !$userseq or (md5($userid."test") != $userseq) or $userip != $REMOTE_ADDR)
	{
		return false;
	}
	else
	{
		return true;
	}

}

?>