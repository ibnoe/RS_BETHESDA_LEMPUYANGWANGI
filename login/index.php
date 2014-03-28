<!html"><?
require_once("lib/loginform.php");
require_once("lib/dbconn.php");
?>
<LINK rel="stylesheet" type="text/css" href="css/redis.css">
<div id="container-log">
    <div id="log-head">Sign In</div>
    <div id="log-box">
        <img src="images/logo.png">
        <div class="log-form">
            <div id="notif">Masukan Username dan Password yang sudah didaftarkan</div>
            <form action="login/process.php" method="post">
                <div class="log-fix"><input name="login_id" type="text" placeholder="Username"></div>
                <div class="log-fix"><input name="login_pass" type="password" placeholder="Password"></div>
                <div class="log-submit"><input type="submit" value="Login"></div>
            </form>
            <?
            if (isset($_SESSION[error])) {
                echo "<div align=\"center\"><font color=red>" . $_SESSION[error] . "</font></div>";
                unset($_SESSION[error]);
            }
            ?>
        </div>
    </div>
</div>
<div id="foot-er">Copyright &copy; 2012 by<b><i>One Medic</i></b>- All Right Reserved</div>
