<?php

namespace App\Http\Controllers;
use App\Login;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

session_start();
class AdminController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
           return Redirect::to('admin.dasboard');
        }else{
            return Redirect::to('/login-admin')->send();
        }
    }
    public function index(){
        return view('admin_login');
    }
    public function admin_register(){
        return view('admin_register');
    }
    public function dashboard(){
        // $this -> AuthLogin();
        return view('admin.dashboard');
    }
    public function check_adminlogin(Request $request)
    {
        $credentials = $request->only('admin_name', 'admin_password');

        if (Auth::guard('admin')->attempt(['admin_name' => $credentials['admin_name'], 'password' => $credentials['admin_password']])) {
            $admin=Login::where('admin_name', $credentials['admin_name'])->first();
            Session::put('admin_id', $admin->admin_id);

            Session::put('admin_name', $credentials['admin_name']);
            return redirect('/dashboard');
        }else{

            Session::put('message','Tài khoản hoặc mật khẩu sai, vui lòng nhập lại');
            return Redirect::to('/login-admin');
        }
    }
    // public function check_adminlogin(Request $request){
    //     $admin_name = $request->admin_name;
    //     $admin_password = md5($request->admin_password);
        
    //     $result = Login::where('admin_name',$admin_name)->where('admin_password',$admin_password)->first();
        
    //     if($result){
    //         Session::put('admin_id', $result->admin_id);
    //         Session::put('admin_name', $result->admin_name);
    //         return Redirect::to('/dashboard');
    //     }else{
    //         Session::put('message','Tài khoản hoặc mật khẩu sai, vui lòng nhập lại');
    //         return Redirect::to('/login-admin');
    //     }
        
    // }
    public function save_admin(Request $request){
        $admin = new Login();
        
        $data = $request->all();
        $check = $admin::where('admin_name',$data['admin_name'])->first();
        if($check){
            Session::put('message','Tài khoản đã tồn tại');
        }else{
            $admin->role_id = $data['role_id'];
            $admin->admin_name = $data['admin_name'];
            $admin->admin_phone = $data['admin_phone'];
            $admin->admin_password = bcrypt($data['admin_password']);
            $admin->save();    
        }
        return Redirect::to('/list-account');
    }
    public function logout(){
        Session::put('admin_name', null);
        Session::put('admin_id',null);

        return Redirect::to('/login-admin');
    }
    public function list_account(){

        $admin_admin_role = DB::table('tbl_admin')
        ->join('tbl_role', 'tbl_admin.role_id', '=', 'tbl_role.role_id')
        ->orderBy('tbl_role.role_id', 'asc')
        ->get();

        $all_role = DB::table('tbl_role')->orderBy('role_id', 'asc')->get();
        return view('admin.account.list_add_account')->with(compact('admin_admin_role','all_role'));
    }
    public function permisstion($role_id, Request $request){
       $admin_id = $request->admin_id;
        if ($admin_id == Session::get('admin_id')) {
            Session::put('permisstion','Không được phân quyền cho chính mình');
        }else {
            DB::table('tbl_admin')->where('admin_id',$admin_id)->update(['role_id' => $role_id]);
            Session::put('permisstion','Phân quyền thành công');
        }
        
        return Redirect()->back();
    }
    public function delete_account($admin_id){
        $admin_role = DB::table('tbl_admin')->where('admin_id',$admin_id)->first();
        if ($admin_id == Session::get('admin_id') || $admin_role->role_id == '1') {
            Session::put('delete_account','Tài khoản này không thể bị xoá');
        }else {
        DB::table('tbl_admin')->where('admin_id',$admin_id)->delete();
            Session::put('delete_account','Xoá thành công');
        }
        return Redirect()->back();
    }
}
