<?

session_start();
session_unregister($useruid);
session_unregister($userip);
session_destroy();

setcookie("userseq","",time() - 3600,"/");

//header("Location: ./index.php");

?>