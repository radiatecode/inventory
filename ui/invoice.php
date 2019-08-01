<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
$report = new Report();
$data=[];$type=null;$order=null;
if (isset($_POST['generate'])){
    $data = $report->generateInvoice($_POST);
    $total = $data['sub_total']-(($data['sub_total']*$data['discount'])/100);
    $vat_amount = ($total*$data['vat'])/100;
    $grand_total = $total+$vat_amount;
    $type = $data['search']['invoice_type']?$data['search']['invoice_type']:'';
    $order = $data['search']['orders']?$data['search']['orders']:'';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
    <link href="../assets/js/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">

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
                    <h3>IMS INVOICE</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php session_message() ?>
                    <!-- Page Container -->
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Invoice <small></small></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form class="form-horizontal" action="invoice.php" method="post">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Invoice Type
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="invoice_type" id="invoice_type">
                                                    <option value="">-- Select Type --</option>
                                                    <option value="purchase">Purchase</option>
                                                    <option value="sales">Sales</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name">Orders
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="orders" id="orders">
                                                    <option value="">-- Select Order --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label" for="last-name"><i class="fa fa-search-plus"></i></label>
                                            <div class="">
                                                <button type="submit" name="generate" class="btn btn-success btn-md"><i class="fa fa-search"></i> Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <br>
                                <?php if (count($data)>0){ ?>
                                <section class="content invoice">
                                    <div id="print_area">
                                    <div class="row">
                                        <div class="col-xs-12 invoice-header">
                                            <h1>
                                                <i class="fa fa-globe"></i> Invoice.
                                                <small class="pull-right">Date: <?= date('Y-m-d') ?></small>
                                            </h1>
                                        </div>

                                    </div>

                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                            From
                                            <address>
                                                <strong><?=  $data['from_name'] ?></strong>
                                                <br><?= $data['from_address'] ?>
                                                <br>Phone: <?= $data['from_phone'] ?>
                                                <br>Email: <?= $data['from_email'] ?>
                                            </address>
                                        </div>

                                        <div class="col-sm-4 invoice-col">
                                            To
                                            <address>
                                                <strong><?= $data['to_name'] ?></strong>
                                                <br><?= $data['to_address'] ?>
                                                <br>Phone: <?= $data['to_phone'] ?>
                                                <br>Email: <?= $data['to_email'] ?>
                                            </address>
                                        </div>

                                        <div class="col-sm-4 invoice-col">
                                            <b>Invoice #<?= $data['order'] ?></b>
                                            <br>
                                            <br>
                                            <b>Order ID:</b> <?= $data['order'] ?>
                                            <br>
                                            <b>Payment Due:</b> <?= $data['due'] ?>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-xs-12 table">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Product</th>
                                                    <th>Model #</th>
                                                    <th>Unit Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $sl=1; foreach ($data['items_data'] as $item){ ?>
                                                    <tr>
                                                        <td><?= $sl; ?></td>
                                                        <td><?= $item['product_name'] ?></td>
                                                        <td><?= $item['model'] ?></td>
                                                        <td><?= $item['unit_price'] ?></td>
                                                        <td><?= $item['quantity'] ?></td>
                                                        <td><?= $item['unit_price']*$item['quantity'] ?></td>
                                                    </tr>
                                                <?php $sl++; } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-xs-6">
                                            <p class="lead">Payment Methods: <?= $data['payment_method'] ?></p>
                                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                                <?= $data['note'] ?>
                                            </p>
                                        </div>

                                        <div class="col-xs-6">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                    <tr>
                                                        <th style="width:50%">Subtotal:</th>
                                                        <td><?= $data['sub_total'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Discount (%)</th>
                                                        <td><?= $data['discount'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total</th>
                                                        <td><?= $total ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Vat (%)</th>
                                                        <td><?= $data['vat'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Vat</th>
                                                        <td><?= $vat_amount ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Grand Total</th>
                                                        <td><?= $grand_total ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Paid</th>
                                                        <td><?= $data['paid'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Due</th>
                                                        <td><?= $data['due'] ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    </div>
                                    <div class="row no-print">
                                        <div class="col-xs-12">
                                            <a target="_blank" href="report.php?invoice_type=<?= $type ?>&orders=<?= $order ?>"
                                               class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-print"></i> Generate PDF</a>
                                        </div>
                                    </div>
                                </section>
                                <?php } ?>
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
<script src="https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<script src="../assets/js/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../assets/js/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
    $('#invoice_type').change(function () {
        var invoice_type = $(this).val();
        var dropdown = '';
        $.ajax({
            url:"ajax.php?ajax=invoice_order&type="+invoice_type,
            type:'GET',
            dataType:'json',
            success:function (response) {
                $('#orders').children('option:not(:first)').remove();
                console.log(response);
                if (invoice_type==="purchase") {
                    $.each(response, function (key, value) {
                        dropdown += '<option value="' + value.purchase_order_no + '">' + value.purchase_order_no + '</option>';
                    });
                }else if(invoice_type==="sales"){
                    $.each(response, function (key, value) {
                        dropdown += '<option value="' + value.sales_order + '">' + value.sales_order + '</option>';
                    });
                }
                $('#orders').append(dropdown);
            },
            error:function (error) {
                console.log(error);
            }
        });
    });
</script>
</body>
</html>
