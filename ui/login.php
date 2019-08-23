<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
redirectIfAuthenticate();

$auth = new Auth();
$auth->credentials($_POST['email'],$_POST['password']);
//echo md5('12345678');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php include('../include/_head.php') ?>
</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div>
            <section class="login_content">
                <?php messages($auth->getMessage()) ?>
                <form action="login.php" method="post">
                    <h1>IMS User Login</h1>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" name="email" value="" required="" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" name="password" required="" />
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Log in</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">New to site?
                            <a href="" class="to_register"> Create Account </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <p>©2016 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>
