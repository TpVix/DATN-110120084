<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Brand;
use App\Category;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;

session_start();

class AccessoryController extends Controller
{
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dasboard');
        } else {
            return Redirect::to('/login-admin')->send();
        }
    }
    public function add_accessory()
    {
        $this->AuthLogin();

        $accessory = DB::table('tbl_accessory')->orderBy('accessory_id','desc')->get();
        return view('admin.accessory.add_accessory')
        ->with('accessory',$accessory);
    }
    public function save_accessory(Request $request)
    {
        $this->AuthLogin();
        $data = array();
        $data['accessory_name'] = $request->accessory_name;
        $data['accessory_slug'] = $request->accessory_slug;
        $data['accessory_status'] = $request->accessory_status;
        DB::table('tbl_accessory')->insert($data);
        Session::put('message', 'Thêm thành công');

        return Redirect::to('/add-accessory');
    }
    public function edit_accessory($accessory_id,Request $request)
    {
      
        $this->AuthLogin();
       
        $accessory = DB::table('tbl_accessory')->orderBy('accessory_id','desc')->get();
        $accessory_edit = DB::table('tbl_accessory')->where('accessory_id',$accessory_id)->first();
        return view('admin.accessory.edit_accessory')
        ->with('accessory',$accessory)
        ->with('accessory_edit',$accessory_edit);


    }
    public function update_accessory($accessory_id,Request $request)
    {
        $this->AuthLogin();
        $data = array();
        $data['accessory_name'] = $request->accessory_name;
        
        $data['accessory_status'] = $request->accessory_status;
        $data['accessory_slug'] = $request->accessory_slug;
        DB::table('tbl_accessory')->where('accessory_id',$accessory_id)->update($data);
        Session::put('message', 'Chỉnh sửa thành công');
        
        return Redirect::to('/add-accessory');


    }
    public function delete_accessory($accessory_id,Request $request)
    {
        $product_accessory = DB::table('tbl_product')
        ->where('accessory_id', $accessory_id)->first();
        if (isset($product_accessory)) {
            Session::put('delete','Không thể xoá do còn thành phần phụ');
            return Redirect::to('/add-accessory');
        }else {
            DB::table('tbl_accessory')->where('accessory_id',$accessory_id)->delete();
            Session::put('delete','Xoá thành công');
            return Redirect::to('/add-accessory');
        }
    }
    public function product_accessory($accessory_id,Request $request)
    {
       Session::put('accessory_id',$accessory_id);
        $all_product = DB::table('tbl_product')
        ->leftJoin('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->where('category_id',0)
        ->orderBy('product_id','desc')->get();

        $accessory_name = DB::table('tbl_accessory')->where('accessory_id',$accessory_id)->first();
       
        $product_accessory = DB::table('tbl_product')
        ->where('accessory_id',$accessory_id)
        ->orderBy('product_id','desc')->get();
        return view('admin.accessory.product_accessory')->with(compact('all_product','accessory_id','accessory_name','product_accessory'));
    }
    public function chose_product_accessory($product_id)
    {
        DB::table('tbl_product')
        ->where('product_id', $product_id)
        ->update(['accessory_id' => Session::get('accessory_id')]);

        Session::put('chose_success','Thêm thành công');
        return Redirect()->back();
    }
    public function delete_product_accessory($product_id)
    {
        DB::table('tbl_product')->where('product_id', $product_id)
        ->update(['accessory_id' => 0]);

        Session::put('chose_success','Xoá thành công');
        return Redirect()->back();
    }


}
