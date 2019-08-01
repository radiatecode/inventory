<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.php" class="site_title"><i class="fa fa-paw"></i> <span>IMS</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="../assets/images/<?php echo Session::get('user','photo') ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo Session::get('user','username') ?></h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a href="index.php"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="stock.php"><i class="fa fa-line-chart"></i> Stock</a></li>
                    <li><a><i class="fa fa-home"></i> Products <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="products-add.php">New</a></li>
                            <li><a href="products.php">List</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Purchase <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="purchase-add.php">New</a></li>
                            <li><a href="purchase.php">List</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Purchase Return<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="purchase-return-add.php">New</a></li>
                            <li><a href="purchase-return.php">List</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Sale <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="order-add.php">New</a></li>
                            <li><a href="orders.php">List</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Sales Return<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="sales-return-add.php">New</a></li>
                            <li><a href="sales-return.php">List</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Catalogue <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="brand.php">Brand</a></li>
                            <li><a href="category.php">Category</a></li>
                            <li><a href="suppliers.php">Supplier</a></li>
                            <li><a href="attributes.php">Attributes</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-home"></i> Customer <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="customer-add.php">New</a></li>
                            <li><a href="customers.php">List</a></li>
                        </ul>
                    </li>
                    <li><a href="invoice.php"><i class="fa fa-globe"></i> Invoice</a></li>
                    <li><a><i class="fa fa-bar-chart"></i> Reports <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="monthly-stock.php">Monthly Stock</a></li>
                            <li><a href="monthly-sales.php">Monthly Sales</a></li>
                            <li><a href="monthly-purchase.php">Monthly Purchase</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.php">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>