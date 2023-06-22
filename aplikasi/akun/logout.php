<?php
logout ();
session_destroy();
//echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
echo '<meta http-equiv="refresh" content="0; url=index.php" />';
?>