<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$customer = new Customers();
if (isset($_GET['id'])){
    if (isset($_POST['update'])){
        $customer->update($_POST,$_FILES,$_GET['id']);
    }
    $data = $customer->customer_info($_GET['id']);
}else{
    abort(404);
}

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
                    <h3>View Customer Info</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                    session_message();
                    messages($customer->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Customer <small>Information View</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="customer-view.php?id=<?= $_GET['id'] ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="col-md-8 col-lg-8 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Full Name <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Email <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="email" id="email" name="email" value="<?= $data['email'] ?>" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Phone <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="phone" name="phone" value="<?= $data['phone'] ?>" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Gender <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <select name="gender" class="form-control col-md-7 col-xs-12">
                                                    <option value="Male" <?= $data['gender']=="Male"?'selected':'' ?>>Male</option>
                                                    <option value="Female" <?= $data['gender']=="Female"?'selected':'' ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Present Address <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <textarea class="form-control" name="present_address" id="present_address"><?= $data['present_address'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <div class="">
                                                <input type="checkbox" id="same_as" name="same_as"> Same As Present Address?
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Permanent Address <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <textarea class="form-control" name="permanent_address" id="permanent_address"><?= $data['permanent_address'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Upload Photo
                                            </label>
                                            <div class="">
                                                <input type="file" id="photo" name="photo" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Photo Preview
                                            </label>
                                            <div class="">
                                                <input type="hidden" name="old_photo" value="<?= $data['photo'] ?>">
                                                <img src="../assets/images/<?= $data['photo'] ?>" id="photo-preview" style="width: 180px;height: 150px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div>
                                            <button type="submit" name="update" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </form>
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
                $('#photo-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#photo").change(function() {
        readURL(this);
    });

</script>
</body>
</html>
