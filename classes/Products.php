<?php
/**
 * Created by PhpStorm.
 * User: Radiate Noor
 * Date: 6/18/2019
 * Time: 5:42 PM
 */
require '../vendor/autoload.php';
class Products
{
    private $_db;
    private $messages=[];
    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function store($post,$files){
         $validation = Validation::PostValidate($post,[
             'brand'=>'required',
             'product_name'=>'required',
             'model'=>'required',
             'description'=>'required',
             'purchase_price'=>'required|number',
             'qty'=>'required|number',
             'sale_price'=>'required|number',
             'mrp'=>'required|number',
             'category'=>'required',
             'attribute'=>'required|array',
             'attribute_value'=>'required|array'
         ]);
         if ($validation){
             $this->messages = $validation;
         }else{
             $rename=null;$pa_insert=null;
             if (!empty($files['thumb']['name'])){
                 $photo = $files['thumb']['name'];
                 $photo_tmp = $files['thumb']['tmp_name'];
                 $extension = pathinfo($photo,PATHINFO_EXTENSION);
                 $rename = time().".".$extension;
                 move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
             }
             $insert = $this->_db->insert('products',[
                 'brand_id'=>$this->_db->escapeString($post['brand']),
                 'category_id'=>$this->_db->escapeString($post['category']),
                 'product_name'=>$this->_db->escapeString($post['product_name']),
                 'model'=>$this->_db->escapeString($post['model']),
                 'product_details'=>$this->_db->escapeString($post['description']),
                 'enable'=>1,
                 'thumb'=>$rename,
                 'purchase_price'=>$this->_db->escapeString($post['purchase_price']),
                 'purchase_discount'=>$this->_db->escapeString($post['purchase_discount']),
                 'qty'=>$this->_db->escapeString($post['qty']),
                 'sale_price'=>$this->_db->escapeString($post['sale_price']),
                 'sale_discount'=>$this->_db->escapeString($post['sale_discount']),
                 'mrp'=>$this->_db->escapeString($post['mrp']),
                 'created_at'=>date('Y-m-d h:i:s')
             ]);
             if (!empty($post['attribute'])){
                 $product_id = $this->_db->lastInsertedID();
                 $attribute = $post['attribute'];
                 $value= $post['attribute_value'];
                 for ($i=0;$i<count($attribute);$i++){
                     $pa_insert = $this->_db->insert('products_attributes',[
                        'product_id'=>$product_id,
                        'attribute_id'=>$this->_db->escapeString($attribute[$i]),
                        'attribute_value'=>$this->_db->escapeString($value[$i]),
                     ]);
                 }
             }
             if ($insert && $pa_insert) {
                 Session::flush('success','Successfully Inserted Product Data');
             }else{
                 Session::flush('failed','Product Data Insertion Error! '.$this->_db->sql_error());
             }
         }
    }

    public function update_basic($post,$id){
        $validation = Validation::PostValidate($post,[
            'brand'=>'required',
            'supplier'=>'required',
            'product_name'=>'required',
            'description'=>'required',
            'product_price'=>'required|number'
        ]);
        if ($validation){
            $this->messages = $validation;
        }else{
            $rename=null;
            if (!empty($files['thumb']['name'])){
                $photo = $files['thumb']['name'];
                $photo_tmp = $files['thumb']['tmp_name'];
                $extension = pathinfo($photo,PATHINFO_EXTENSION);
                $rename = time().".".$extension;
                move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
                $this->_db->update('products',[
                    'thumb'=>$rename
                ])->where('id','=',$id)
                ->get();
            }
            $update = $this->_db->update('products',[
                'brand_id'=>$this->_db->escapeString($post['brand']),
                'supplier_id'=>$this->_db->escapeString($post['supplier']),
                'product_name'=>$this->_db->escapeString($post['product_name']),
                'product_details'=>$this->_db->escapeString($post['description']),
                'product_price'=>$this->_db->escapeString($post['product_price'])
            ])->where('id','=',$id)
            ->get();

            if ($update){
                Session::flush('success','Successfully Updated Product Information');
            }else{
                Session::flush('failed','Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function update_attributes($post,$id){
        $pa_update=null;$pa_insert=null;
        if (!empty($post['product_attribute_id'][0])){
            $attribute_id = $post['product_attribute_id'];
            $update_attribute = $post['update_attribute'];
            $update_attribute_value = $post['update_attribute_value'];
            for ($i=0;$i<count($attribute_id);$i++){
                $pa_update = $this->_db->update('products_attributes',[
                    'attribute_id'=>$this->_db->escapeString($update_attribute[$i]),
                    'attribute_value'=>$this->_db->escapeString($update_attribute_value[$i]),
                ])
                ->where('id','=',$attribute_id[$i])
                ->get();
                if (!$pa_update){
                    $this->messages [] = "Product Attributes Update Error. ".$this->_db->sql_error();
                }
            }
            Session::flush('success','Successfully Updated Product Attributes');
        }
        if (!empty($post['attribute'][0])){
            $attribute = $post['attribute'];
            $value= $post['attribute_value'];
            for ($i=0;$i<count($attribute);$i++){
                $pa_insert = $this->_db->insert('products_attributes',[
                    'product_id'=>$id,
                    'attribute_id'=>$this->_db->escapeString($attribute[$i]),
                    'attribute_value'=>$this->_db->escapeString($value[$i]),
                ]);
                if (!$pa_insert){
                    $this->messages [] = "Product Attributes New Insertion Error. ".$this->_db->sql_error();
                }
            }
            Session::flush('success','Successfully Inserted Attributes For The Product');
        }

    }

    public function update_stock($post){
        $purchase_update=null;
        if (!empty($post['purchase_id'][0])){
            $purchase_id = $post['purchase_id'];
            $qty = $post['qty'];
            foreach ($purchase_id as $i=>$value){
                $purchase_update = $this->_db->update('purchase',[
                    'qty'=>$this->_db->escapeString($qty[$i])
                ])
                ->where('id','=',$purchase_id[$i])
                ->get();
                if (!$purchase_update){
                    $this->messages[] = 'Purchase Stock Update Error. '.$this->_db->sql_error();
                }
            }
            Session::flush('success','Successfully Purchase Stock Updated');
        }
    }

    public function getMessage(){
        return count($this->messages)>0?$this->messages:[];
    }

    public function allProducts(){
        $products = $this->_db->select(['products.*','brands.brand_name',
            'categories.name'])
            ->table('products')
            ->join('brands','brands.id','products.brand_id')
            ->join('categories','categories.id','products.category_id')
            ->orderBy('products.id','DESC')
            ->get();
        if (!$products){
            Session::flush('failed','Query Error! '.$this->_db->sql_error());
        }
        return $products;
    }

    public function stock(){
        $products = $this->_db->select(['products.*','brands.brand_name',
            'categories.name','suppliers.name AS supplier','sum(qty) AS product_qty'])
            ->table('products')
            ->join('brands','brands.id','products.brand_id')
            ->join('categories','categories.id','products.category_id')
            ->join('suppliers','suppliers.id','products.supplier_id')
            ->leftJoin('purchase','purchase.product_id','products.id')
            ->groupBy('purchase.product_id')
            ->orderBy('products.id','DESC')
            ->get();
        if (!$products){
            Session::flush('failed','Stock Query Error! '.$this->_db->sql_error());
        }
        return $products;
    }

    public function viewProduct($id){

        $product = $this->_db->select(['products.*','name'])
            ->table('products')
            ->leftJoin('categories','products.category_id','categories.id')
            ->where('products.id','=',$id)
            ->get();
        if ($this->_db->numRows($product)==0){
            $this->messages[] = 'Product Query Error '.$this->_db->sql_error();
        }

        $product_attributes = $this->_db->select(['products_attributes.*'])
            ->table('products_attributes')
            ->where('product_id','=',$id)
            ->get();
        if ($this->_db->numRows($product)==0){
            $this->messages[] = 'Attributes Query Error '.$this->_db->sql_error();
        }

        if ($product && $product_attributes){
            $data = [
                'product'=>$this->_db->fetchAssoc($product),
                'attributes'=>$this->_db->fetchAll($product_attributes)
            ];
            return $data;
        }

        return false;
    }

    public function purchase($product_id,$qty){
        $insert = $this->_db->insert('purchase',[
            'product_id'=>$product_id,
            'qty'=>$qty,
            'purchase_date'=>date('Y-m-d h:i:s')
        ]);

        if (!$insert){
            return $this->_db->sql_error();
        }

        return 'success';
    }

    public function delete_stock($id){
        $delete = $this->_db->delete('purchase')
            ->where('id','=',$id)
            ->get();

        if (!$delete){
            return $this->_db->sql_error();
        }
        return true;
    }

    public function __destruct()
    {
        $this->_db->close();
    }


}