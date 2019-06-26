<?php
require '../vendor/autoload.php';
Session::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link href="../assets/js/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="../assets/js/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <link href="../assets/js/nprogress/nprogress.css" rel="stylesheet">

    <link href="../assets/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-12">
            <div class="col-middle">
                <div class="text-center text-center">
                    <h1 class="error-number"><?= Session::get('error','code')?Session::get('error','code'):404 ?></h1>
                    <h2><?= Session::get('error','title')?Session::get('error','title'):'Denied' ?></h2>
                    <p><?= Session::get('error','details')?Session::get('error','details'):'Denied The Request' ?></p>
                    <div class="mid_center">
                        <a href="index.php" class="btn btn-success btn-md"><i class="fa fa-home"></i> Go Back To Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</html>