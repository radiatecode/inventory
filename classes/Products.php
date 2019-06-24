<?php
/**
 * Created by PhpStorm.
 * User: lenovo
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
             'supplier'=>'required',
             'product_name'=>'required',
             'description'=>'required',
             'product_price'=>'required|number',
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
                 'supplier_id'=>$this->_db->escapeString($post['supplier']),
                 'product_name'=>$this->_db->escapeString($post['product_name']),
                 'product_details'=>$this->_db->escapeString($post['description']),
                 'enable'=>1,
                 'thumb'=>$rename,
                 'product_price'=>$this->_db->escapeString($post['product_price']),
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
            }
            $update = $this->_db->update('products',[
                'brand_id'=>$this->_db->escapeString($post['brand']),
                'supplier_id'=>$this->_db->escapeString($post['supplier']),
                'product_name'=>$this->_db->escapeString($post['product_name']),
                'product_details'=>$this->_db->escapeString($post['description']),
                'thumb'=>$rename,
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
        if (!empty($post['product_attribute_id'])){
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
        if (!empty($post['purchase_id'])){
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
            Session::flush('failed','Query Error! '.$this->_db->sql_error());
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

        $purchased = $this->_db->select(['purchase.*'])
            ->table('purchase')
            ->where('product_id','=',$id)
            ->get();
        if ($this->_db->numRows($product)==0){
            $this->messages[] = 'Purchased Query Error '.$this->_db->sql_error();
        }
        if ($product && $product_attributes && $purchased){
            $data = [
                'product'=>$this->_db->fetchAssoc($product),
                'attributes'=>$this->_db->fetchAll($product_attributes),
                'stock'=>$this->_db->fetchAll($purchased)
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

    public function __destruct()
    {
        $this->_db->close();
    }


}