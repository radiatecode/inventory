<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../vendor/autoload.php';

$product = new Products();
$brand = new Brand();
$category = new Category();
$supplier = new Suppliers();
if (isset($_GET['id'])){
    $data = $product->viewProduct($_GET['id']);
    if ($data){
        $view_product = $data['product'];
        $view_attributes = $data['attributes'];
        $view_stock = $data['stock'];
    }
}else{
    echo "404 page";
}
if (isset($_POST['submit'])){
    $product->store($_POST,$_FILES);
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
                                <form action="products-add.php" method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Basic</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="attributes-tab" data-toggle="tab" aria-expanded="false">Attributes</a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="stock-tab" data-toggle="tab" aria-expanded="false">Product Stock</a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                                <div class="col-md-8 col-lg-8 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Brands <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                           <select name="brand" id="brand" class="form-control">
                                                               <option value="">-- Select Brand --</option>
                                                               <?php foreach ($brand->allBrands() as $row){ ?>
                                                                  <option value="<?= $row['id'] ?>"><?= $row['brand_name'] ?></option>
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
                                                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Name <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <input type="text" id="product_name" name="product_name" class="form-control col-md-7 col-xs-12">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Details <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <textarea name="description" id="description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label" for="last-name">Product Price <span class="required">*</span>
                                                        </label>
                                                        <div class="">
                                                            <input type="text" id="product_price" name="product_price" class="form-control col-md-7 col-xs-12">
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
                                                            <img src="" id="photo-preview" style="width: 180px;height: 150px">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="attributes-tab">
                                                <div class="form-group">
                                                    <label class="control-label" for="last-name">Category <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        <select name="category" id="category" class="form-control">
                                                            <option value="">-- Select Category --</option>
                                                            <?php foreach ($category->allCategories() as $row){ ?>
                                                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
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

                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="stock-tab">
                                                <div class="form-group">
                                                    <label class="control-label" for="last-name">Category <span class="required">*</span>
                                                    </label>
                                                    <div class="">
                                                        <select name="category" id="category" class="form-control">
                                                            <option value="">-- Select Category --</option>
                                                            <?php foreach ($category->allCategories() as $row){ ?>
                                                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
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

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xs-12">
                                        <div>
                                            <button type="submit" name="submit" id="submit" class="btn btn-success btn-md"><i class="fa fa-save"></i> Save</button>
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

</script>
</body>
</html>
