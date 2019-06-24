<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

$product = new Products();
$brand = new Brand();
$category = new Category();
$supplier = new Suppliers();
$view_product = [];
$view_attributes = [];
$view_stock = [];
$category_attributes = [];

if (isset($_GET['id']) && isset($_POST['update_basic'])){
    $product->update_basic($_POST,$_GET['id']);
}elseif (isset($_GET['id']) && isset($_POST['update_attributes'])){
    $product->update_attributes($_POST,$_GET['id']);
}elseif (isset($_GET['id']) && isset($_POST['update_stock'])){
    $product->update_basic($_POST,$_GET['id']);
}
if (isset($_GET['id'])){
    $data = $product->viewProduct($_GET['id']);
    if ($data){
        $view_product = $data['product'];
        $view_attributes = $data['attributes'];
        $view_stock = $data['stock'];
        $attributes = new ProductAttributes();
        $category_attributes = $attributes->getAttributesByCategory($view_product['category_id']);
    }
}else{
    echo "404 page";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../include/_head.php') ?>
</head>

<body class="nav-md" onload="loadAttributes()">
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
                    <h3>View Product</h3>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <?php
                       session_message();
                       messages($product->getMessage())
                    ?>
                    <!-- Page Container -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Product <small>View</small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Basic</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="attributes-tab" data-toggle="tab" aria-expanded="false">Attributes</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="stock-tab" data-toggle="tab" aria-expanded="false">Product Stock</a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                                <form action="products-view.php?id=<?= $_GET['id'] ?>" method="post" enctype="multipart/form-data">
                                                    <div class="col-md-8 col-lg-8 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Brands <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                           <select name="brand" id="brand" class="form-control">
                                                               <option value="">-- Select Brand --</option>
                                                               <?php foreach ($brand->allBrands() as $row){ ?>
                                                                  <option value="<?= $row['id'] ?>" <?= $view_product['brand_id']==$row['id']?'selected':'' ?>><?= $row['brand_name'] ?></option>
                                                               <?php } ?>
                                                           </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Supplier <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <select name="supplier" id="supplier" class="form-control">
                                                                <option value="">-- Select Supplier --</option>
                                                                <?php foreach ($supplier->allSuppliers() as $row){ ?>
                                                                    <option value="<?= $row['id'] ?>" <?= $view_product['supplier_id']==$row['id']?'selected':'' ?>><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Name <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <input type="text" id="product_name" name="product_name" value="<?= $view_product['product_name'] ?>" class="form-control col-md-7 col-xs-12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Details <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <textarea name="description" id="description"><?= $view_product['product_details'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Price <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <input type="text" id="product_price" name="product_price" value="<?= $view_product['product_price'] ?>" class="form-control col-md-7 col-xs-12">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <div class="col-md-4 col-lg-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Thumb <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <input type="file" id="thumb" name="thumb" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Thumb Preview
                                                        </label>
                                                        <div class="">
                                                            <img src="../assets/images/<?= $view_product['thumb'] ?>" id="photo-preview" style="width: 180px;height: 150px">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                                        <br>
                                                        <div>
                                                            <button type="submit" name="update_basic" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Update Basic</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="attributes-tab">
                                                <form action="products-view.php?id=<?= $_GET['id'] ?>" method="post">
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Category <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <select name="category" id="category" class="form-control">
                                                                <option value="">-- Select Category --</option>
                                                                <?php foreach ($category->allCategories() as $row){ ?>
                                                                    <option value="<?= $row['id'] ?>" <?= $view_product['category_id']==$row['id']?'selected':'' ?>><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php if (count($view_attributes)>0){ ?>
                                                             <div class="">
                                                                <br>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Value</th>
                                                                        <th></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($view_attributes as $attr){ ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox" name="product_attribute_id[]" value="<?= $attr['id'] ?>">
                                                                            </td>
                                                                            <td>
                                                                                <select name="update_attribute[]" class="form-control">
                                                                                    <option value="">-- Select Attribute --</option>
                                                                                    <?php foreach ($category_attributes as $row){ ?>
                                                                                        <option value="<?= $row['id'] ?>"
                                                                                            <?= $attr['attribute_id']==$row['id']?'selected':'' ?>><?= $row['attribute_name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="update_attribute_value[]" value="<?= $attr['attribute_value'] ?>" class="form-control col-md-7 col-xs-12">
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" id="<?= $attr['id'] ?>" class="btn btn-danger btn-xs delete_attributes"><i class="fa fa-trash-o"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                             </div>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Attributes
                                                            <span >
                                                                <button type="button" id="add_attribute" class="btn btn-success btn-xs">
                                                                    <i class="fa fa-plus-circle"></i>
                                                                </button>
                                                            </span>
                                                        </label>
                                                        <div id="attribute_row" class="">
                                                            <br>
                                                            <table>
                                                                <thead>
                                                                <tr>
                                                                    <th>Attributes Name</th>
                                                                    <th>Attributes Value</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                 <tr>
                                                                     <td>
                                                                         <select name="attribute[]" id="constant_attribute" class="form-control attribute">
                                                                             <option value="">-- Select Attribute --</option>

                                                                         </select>
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="attribute_value[]" class="form-control col-md-7 col-xs-12 attribute_value">
                                                                     </td>
                                                                 </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                                        <div>
                                                            <button type="submit" name="update_attributes" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Update Attributes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="stock-tab">
                                                <form action="products-view.php?id=<?= $_GET['id'] ?>" method="post">
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Stock
                                                        </label>
                                                        <div id="attribute_row" class="">
                                                            <br>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th style="width: 50px">#</th>
                                                                    <th style="width: 150px">Purchase Qty</th>
                                                                    <th style="width: 150px">Purchase Date</th>
                                                                    <th ></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($view_stock as $row){ ?>
                                                                 <tr>
                                                                     <td>
                                                                         <input type="checkbox" name="purchase_id[]" value="<?= $row['id'] ?>">
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="qty[]" value="<?= $row['qty'] ?>" class="form-control col-md-7 col-xs-12 attribute_value">
                                                                     </td>
                                                                     <td>
                                                                         <?= $row['purchase_date'] ?>
                                                                     </td>
                                                                     <td>
                                                                        <button type="button" class="btn btn-danger btn-xs delete_stock"><i class="fa fa-trash-o"></i></button>
                                                                     </td>
                                                                 </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                                        <div>
                                                            <button type="submit" name="update_stock" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Update Stock</button>
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
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>
<?php include('../include/_script.php') ?>
<script src="//cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'description' );
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photo-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#thumb").change(function() {
        readURL(this);
    });
    var attribute_dropdown = '';
    $('#category').change(function () {
        var cat_id = $(this).val();
        $.ajax({
            url:'ajax.php?cat_id='+cat_id,
            type:'GET',
            dataType:'json',
            success:function (response) {
                $('.attribute').children('option:not(:first)').remove();
                attribute_dropdown = '';
                $.each(response,function (key,value) {
                    attribute_dropdown += '<option value="'+value.id+'" selected="selected">'+value.attribute_name+'</option>';
                });
                $('.attribute').append(attribute_dropdown);
            },
            error:function (error) {
                console.log(error);
            }
        });
    });
    $("#add_attribute").click(function(){
        $("table tbody").append('<tr><td><select name="attribute[]" class="form-control"><option value="">-- Select Attribute --</option>'+attribute_dropdown+'</select>' +
            '</td><td><input type="text" name="attribute_value[]" required="required" class="form-control col-md-7 col-xs-12 attribute_value"></td>' +
            '<td><button type="button" class="btn btn-danger btn-xs remCF"><i class="fa fa-trash-o"></i></button></td></tr>');
    });
    $("table").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    function loadAttributes() {
        var cat_id = "<?= $view_product['category_id'] ?>";
        if (cat_id) {
            $.ajax({
                url: 'ajax.php?cat_id=' + cat_id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    $('.attribute').children('option:not(:first)').remove();
                    attribute_dropdown = '';
                    $.each(response, function (key, value) {
                        attribute_dropdown += '<option value="' + value.id + '">' + value.attribute_name + '</option>';
                    });
                    $('.attribute').append(attribute_dropdown);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }

    $('.delete_attributes').click(function () {
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then(function(result) {
            if (result.value) {
                var url = "ajax.php?ajax=dpa&id="+id;
                $.ajax({
                    url:url,
                    type:'GET',
                    contentType:false,
                    processData:false,
                    beforeSend:function () {
                        Swal.fire({
                            title: 'Deleting Data.......',
                            showConfirmButton: false,
                            html: '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>',
                            allowOutsideClick: false
                        });
                    },
                    success:function (response) {
                        console.log(response);
                        if (response==="success"){
                            Swal.fire({
                                title: 'Successfully Deleted',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then(function(result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error:function (error) {
                        Swal.close();
                        console.log(error);
                    }
                })
            }
        });
    });

</script>
</body>
</html>
