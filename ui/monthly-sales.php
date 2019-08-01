<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$report = new Report();
$search_option = $report->search_options();
$data = [];$data_result=[];
if (isset($_POST['search'])){
    $data_result = $report->sales_search($_POST);
    $data = $data_result['data_result'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
    <link href="../assets/js/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/js/bootstrap-datepicker/css/bootstrap-datepicker.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
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
                    <h3>Monthly Product Sales</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                        session_message();
                        messages($report->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Monthly Products <small> Sales</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal" action="monthly-sales.php" method="post">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Start Date <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="start_date" name="start_date" class="form-control col-md-7 col-xs-12"
                                                       value="<?= $data_result?$data_result['search']['start_date']:'' ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">End Date <span class="required">*</span>
                                            </label>
                                            <div class="">
                                                <input type="text" id="end_date" name="end_date" class="form-control col-md-7 col-xs-12"
                                                       value="<?= $data_result?$data_result['search']['end_date']:'' ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Category (optional)
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="categories">
                                                    <option value="">-- Select Category --</option>
                                                    <?php foreach ($search_option['categories'] as $category){ ?>
                                                        <option value="<?= $category['id'] ?>" <?= $data_result?$data_result['search']['categories']==$category['id']?'selected':'':'' ?>><?= $category['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Brand (optional)
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="brands">
                                                    <option value="">-- Select Brand --</option>
                                                    <?php foreach ($search_option['brands'] as $brand){ ?>
                                                        <option value="<?= $brand['id'] ?>" <?= $data_result?$data_result['search']['brands']==$brand['id']?'selected':'':'' ?>><?= $brand['brand_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name"><i class="fa fa-search-plus"></i></label>
                                            <div class="">
                                                <button type="submit" name="search" class="btn btn-success btn-md"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <br>
                                <div class="table-responsive">
                                    <table id="monthly_report" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category</th>
                                            <th style="background-color: #8ad6cf; color: black">Product Name</th>
                                            <th>Brand</th>
                                            <th style="background-color: #d63e41; color: white">Sales Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                         <?php
                                         $total_sales = 0;
                                         if (count($data)>0){
                                             $sl=1; foreach ($data as $item) { ?>
                                             <tr>
                                                 <td><?= $sl ?></td>
                                                 <td><?= $item['category_name'] ?></td>
                                                 <td style="background-color: #8ad6cf; color: black"><?= $item['product_name'] ?></td>
                                                 <td><?= $item['brand_name'] ?></td>
                                                 <td  style="background-color: #d63e41; color: white"><?= $item['sales_qty'] ?></td>
                                             </tr>
                                         <?php $sl++; $total_sales += $item['sales_qty'];
                                             }
                                         } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th>Total =</th>
                                            <th><?= $total_sales; ?></th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
<script src="../assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="../assets/js/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../assets/js/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/js/datatables.net/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/buttons.flash.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/jszip.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/pdfmake.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/vfs_fonts.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/buttons.html5.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.net/buttons.print.min.js"></script>
<script>
    $('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        endDate: "",
        todayHighlight: true,
        autoclose: true
    });
    $('#end_date').datepicker({
        format: 'yyyy-mm-dd',
        endDate: "",
        todayHighlight: true,
        autoclose: true
    });
    $('#monthly_report').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: "excel",
                className: "btn-sm"
            },
            {
                extend: "pdfHtml5",
                className: "btn-sm",
                title: 'Stock Summery ( Monthly Report )'
            },

        ]
    } );
</script>
</body>
</html>
