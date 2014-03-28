<?
session_start();
$_SESSION[sid] = session_id();


if (($_POST[login_id]) && ($_POST[login_pass])) {
    require_once("../lib/dbconn.php");

    if (strlen($_POST[login_id]) < 1 || strlen($_POST[login_id]) > 14 || strlen($_POST[login_pass]) < 1
        || strlen($_POST[login_pass]) > 20) {
	$_SESSION[error] = "Password salah.";
    } else {
	$md5paz = md5($_POST[login_pass]);
	$checkUid = pg_query("SELECT uid FROM rs99995 WHERE uid='".$_POST[login_id]."'");
	$rowUid = pg_num_rows($checkUid);
	$checkAll = pg_query("SELECT uid, password FROM rs99995 WHERE uid='".$_POST[login_id]."' AND password='$md5paz'");
	$rowAll = pg_num_rows($checkAll);

	if ((!$rowUid) || ($rowUid == 0)) {
	    $_SESSION[error] = "Username tidak ada.";
	} elseif ((!$rowAll) || ($rowAll == 0)) {
	    $_SESSION[error] = "Password salah.";
	} elseif ($rowAll == 1) {
	    //unset($_SESSION[error]);
	    $vpaz = $md5paz;
	    $getInfo = pg_query("SELECT * FROM rs99995 WHERE uid='".$_POST[login_id]."' AND password='$vpaz'");
	    $rowInfo = pg_fetch_object($getInfo);
	    $_SESSION[uid] = $rowInfo->uid;
	    $_SESSION[vuid] = md5($_SESSION[sid].$_SESSION[uid]);
	    
	    $_SESSION[nama_usr] = $rowInfo->nama; /* Add By Yudha 19042007 */	    
	    $_SESSION[gr] = trim($rowInfo->grup_id); /* Add By Yudha 19042007 */

	}
    }
} else {
    $_SESSION[error] = "Password salah.";
}


if (empty($_SESSION[error])) {
    header("Location: ../index3.php?user=".$_SESSION[uid]);
} else {
    header("Location: ../index3.php?user=".$_SESSION[uid]);
}


echo "<br><br><br><br>";
echo "<center><font class=form_title><b>proses...</b></font></center>";


?>
