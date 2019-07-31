<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 6/17/2019
 * Time: 3:41 PM
 */
require '../vendor/autoload.php';
class Brand
{
    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        Session::start();
        redirectIfNotAuthenticate();
    }

    public function store($post,$files){
       if (isset($post['brand_name']) && !empty($files['photo']['name'])){

           $photo = $files['photo']['name'];
           $photo_tmp = $files['photo']['tmp_name'];
           $extension = pathinfo($photo,PATHINFO_EXTENSION);
           $rename = time().".".$extension;

           $insert = $this->_db->insert('brands',[
               'brand_name'=>$post['brand_name'],
               'logo'=>$rename,
               'enable'=>1
           ]);

           if ($insert) {
               move_uploaded_file($photo_tmp, "../assets/images/" . $rename);
               Session::flush('success','Successfully Inserted Brand Data');
           }else{
               Session::flush('failed','Brand Data Insertion Error!');
           }
       }
    }

    public function update($post,$files=null){
        if (isset($post['brand_name'])){

            $update = $this->_db->update('brands', [
                  'brand_name'=>$post['brand_name']
                ])
                ->where('id','=',$post['edit_id'])
                ->get();

            if (!empty($files['photo']['name'])){
                var_dump($files['photo']['name']);
                $photo = $files['photo']['name'];
                $photo_tmp = $files['photo']['tmp_name'];
                $extension = pathinfo($photo,PATHINFO_EXTENSION);
                $rename = time().".".$extension;
                $update = $this->_db->update('brands', [
                        'logo'=>$rename
                    ])
                    ->where('id','=',$post['edit_id'])
                    ->get();
                move_uploaded_file($photo_tmp,'../assets/images/'.$rename);
            }

            if ($update) {
                Session::flush('success','Successfully Updated Brand Data');
            }else{
                Session::flush('failed','Brand Data Update Error! '.$this->_db->sql_error());
            }
        }
    }

    public function enableBrands(){
        $brands = $this->_db->select(['brands.*'])
            ->table('brands')
            ->where('enable','=',1)
            ->orderBy('id','DESC')
            ->get();
        return $brands;
    }

    public function allBrands(){
        $brands = $this->_db->all('brands');
        return $brands;
    }

    public function delete_brand($id){
        $result = $this->_db->delete('brands')
            ->where('id','=',$id)
            ->get();
        if (!$result){
            return false;
        }
        return true;
    }

    public function enable_disable($id,$type){
        $result='';
        if ($type=='enable') {
            $result = $this->_db->update('brands', [
                'enable' =>1
            ])->where('id', '=', $id)->get();
        }else{
            $result = $this->_db->update('brands', [
                'enable' =>0
            ])->where('id', '=', $id)->get();
        }
        if (!$result){
            return false;
        }
        return true;
    }

}