<?php

if (!cek_login()) { ?>

<!-- Loader -->
<div class="loader">
    <div class="loader_div"></div>
</div>

<!-- Login page -->
<div class="login_wrapper">
    <div class="container">
        <a class="logo_login"> <img src="images/LOGO-IAIC-2021.png" alt="" width="100" height="100"></a><br /><br />
        <div class="login_box">

            <a href="#" class="logo_text">
                <img src="images/logo-login.png" width="90%" height="20%">
            </a>
            <div class="login_form">
                <div class="login_form_inner">
                    <h3>SILAHKAN LOGIN</h3>
                    <form action="" id="formlogin" name="formlogin" method="POST">
                        <div class="form-group">
                            <input type="text" name="username" class="input-text" required placeholder="Username">
                            <span class="focus-border"></span>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="input-text" required placeholder="Password">
                            <span class="focus-border"></span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-md btn-theme btn-block">Login</button>
                        </div>
                    </form>
                    <div class="or_text"><span><B>SISTEM AKADEMIK </B></span></div>
                    <?php
                    login_validate();
                    if (isset($_POST['username'])) {
                        echo web_login();
                    }
                    ?>
                </div>
            </div>
        </div><br /><br />
        <a class="logo_login"> <img src="images/logo-tageline-putih.png" alt="" width="25%" height="10%"></a><br />
    </div>
</div>


<script>
function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>
<?php } ?>