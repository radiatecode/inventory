<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$user = new User();
if (isset($_POST['profile_update'])){
    $user->update_profile($_POST,$_FILES);
}elseif (isset($_POST['update_password'])){
    $user->password_update($_POST);
}
$info = $user->get_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- Side Bar -->
        <?php include('../include/_side-nav.php') ?>
        <!-- /Side Bar -->

        <!-- top navigation -->
        <?php include('../include/_top-nav.php')  ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="title_left">
                    <h3>User Profile</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>user <small> information</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#profile_with_icon_title" data-toggle="tab" aria-expanded="false">
                                            <i class="fa fa-male"></i> PROFILE
                                        </a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#password_with_icon_title" data-toggle="tab" aria-expanded="false">
                                            <i class="fa fa-edit"></i> Change Password
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="profile_with_icon_title">
                                        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                                            <div class="profile_img">
                                                <div id="crop-avatar">

                                                    <img class="img-responsive avatar-view" src="../assets/images/<?= $info['photo'] ?>" alt="Avatar" title="Change the avatar">
                                                </div>
                                            </div>
                                            <h3><?= $info['name'] ?></h3>
                                            <ul class="list-unstyled user_data">
                                                <li>
                                                    <i class="fa fa-briefcase user-profile-icon"></i> role: <?= $info['role'] ?>
                                                </li>
                                                <li class="m-top-xs">
                                                    <i class="fa fa-envelope"></i> <?= $info['email'] ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="profile_title">
                                                <div class="col-md-6">
                                                    <h2>User Information</h2>
                                                </div>
                                            </div>
                                            <form action="profile.php" method="post" enctype="multipart/form-data">
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="password_2">Name</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" name="name" value="<?= $info['name'] ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="email_address_2">Email Address</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="email" name="email" value="<?= $info['email'] ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="email_address_2">Username</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" name="username" value="<?= $info['username'] ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="password_2">Upload Photo</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="file" id="user_photo" name="user_photo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="password_2">Preview Photo</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <img src="" style="width: 200px; height: 200px" id="user_avatar_preview" class="user_avatar_preview">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                  <button class="btn btn-success" type="submit" name="profile_update" value="yes"><i class="fa fa-save"></i> Save Changes</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="password_with_icon_title">
                                        <br><br>
                                        <div class="row">
                                        <div class="col-md-7">
                                            <form class="form-horizontal" action="profile.php" method="post">
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="email_address_2">New Password</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="password" name="password" id="password" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="email_address_2">Confirm Password</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                                    <label for="email_address_2">Do You Want To Log Out After Password Change?</label>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="checkbox" name="logout_confirmation" value="yes">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                    <button class="btn btn-info" type="submit" name="update_password" id="update_password" value="yes"><i class="fa fa-save"></i> Update Password</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <?php require_once '../include/_footer.php'?>
        <!-- /footer content -->
    </div>
</div>
<?php include('../include/_script.php') ?>

<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#user_avatar_preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#user_photo").change(function() {
        readURL(this);
    });


    $("#update_password").click(function (event) {
        if( !confirm('Are you sure that you want to submit the form') ){
            event.preventDefault();
        }
        var password =  $('input[name=password]').val();
        var password_confirmation =  $('input[name=password_confirmation]').val();
        if (password_confirmation!==password) {
             alert('password not match');
             return false;
        }
    });
</script>
</body>
</html>
