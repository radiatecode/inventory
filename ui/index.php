<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';
redirectIfNotAuthenticate();
$dashboard = new Dashboard();
$data = $dashboard->data();
$purchase_chart = $dashboard->purchase_chart();
$sales_chart = $dashboard->sales_chart();
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
                    <h3>Dashboard</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php messages([]) ?>
                    <!-- Page Container -->
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                            <div class="count"><?= $data['purchase_qty']?round($data['purchase_qty']):0; ?></div>
                            <h3>Purchased</h3>
                            <p>Total Purchased Product</p>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-cart-plus"></i></div>
                            <div class="count"><?= $data['sales_qty']?round($data['sales_qty']):0; ?></div>
                            <h3>Sales</h3>
                            <p>Total Sales</p>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                            <div class="count"><?= $data['purchase_return_qty']?round($data['purchase_return_qty']):0; ?></div>
                            <h3>Purchase Return</h3>
                            <p>Total Purchase Return</p>
                        </div>
                    </div>
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-cart-arrow-down"></i></div>
                            <div class="count"><?= $data['sales_return_qty']?round($data['sales_return_qty']):0; ?></div>
                            <h3>Sales Return</h3>
                            <p>Total Sales Return</p>
                        </div>
                    </div>
                    <!-- / End Page Container -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Purchase<small>Chart</small></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
                                    <canvas id="purchase_chart" width="484" height="242" style="width: 484px; height: 242px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Sales<small>Chart</small></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content"><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px;"></iframe>
                                    <canvas id="sales_chart" width="484" height="242" style="width: 484px; height: 242px;"></canvas>
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
<script src="../assets/js/Chart.js/dist/Chart.min.js"></script>
<script>
    if ($('#purchase_chart').length ){
        var chart_data = JSON.parse('<?php echo json_encode($purchase_chart) ?>');
        console.log(chart_data);
        var ctx = document.getElementById("purchase_chart");
        var purchase_chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June",
                    "July","August","September","October","November","December"],
                datasets: [{
                    label: "Purchase Monthly Data",
                    backgroundColor: "rgba(38, 185, 154, 0.31)",
                    borderColor: "rgba(38, 185, 154, 0.7)",
                    pointBorderColor: "rgba(38, 185, 154, 0.7)",
                    pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointBorderWidth: 1,
                    data: [
                        chart_data.January, chart_data.February, chart_data.March,
                        chart_data.April, chart_data.May, chart_data.June,
                        chart_data.July,chart_data.August,chart_data.September,
                        chart_data.October,chart_data.November,chart_data.December
                    ]
                }]
            },
        });

    }
    if ($('#sales_chart').length ){
        var sales_chart_data = JSON.parse('<?php echo json_encode($sales_chart) ?>');

        var ctx = document.getElementById("sales_chart");
        var sales_chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June",
                    "July","August","September","October","November","December"],
                datasets: [{
                    label: "Sales Monthly Data",
                    backgroundColor: "rgba(38, 185, 154, 0.31)",
                    borderColor: "rgba(38, 185, 154, 0.7)",
                    pointBorderColor: "rgba(38, 185, 154, 0.7)",
                    pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointBorderWidth: 1,
                    data: [
                        sales_chart_data.January, sales_chart_data.February, sales_chart_data.March,
                        sales_chart_data.April, sales_chart_data.May, sales_chart_data.June,
                        sales_chart_data.July,sales_chart_data.August,sales_chart_data.September,
                        sales_chart_data.October,sales_chart_data.November,sales_chart_data.December
                    ]
                }]
            },
        });

    }
</script>
</body>
</html>
