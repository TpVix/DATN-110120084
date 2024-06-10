<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Customer;
use App\Brand;
use App\Category;
use Mail;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $seven_days_ago = Carbon::now()->subDays(7);
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $new_product = DB::table('tbl_product')
            ->whereBetween('created_at', [$seven_days_ago, Carbon::now()])
            ->get();


        $slider_large = DB::table('tbl_slider')->where('slider_option', 1)->where('slider_status', 1)->get();
        $slider_short = DB::table('tbl_slider')->where('slider_option', 2)->where('slider_status', 1)->get();
        if (Session::get('customer_id')) {
            $history_product = DB::table('tbl_order')
                ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
                ->join('tbl_order_detail', 'tbl_order_detail.order_id', '=', 'tbl_order.order_id')
                ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_order_detail.product_id')
                ->where('tbl_order.customer_id', Session::get('customer_id'))
                ->get()->unique('product_id')->values();


            $product_ids = [];
            $order_ids = [];
            foreach ($history_product as $key => $v_history_product) {
                $product_ids[] = $v_history_product->product_id;

            }
            $order_with_product_ids = DB::table('tbl_order')
                ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
                ->join('tbl_order_detail', 'tbl_order_detail.order_id', '=', 'tbl_order.order_id')
                ->whereIn('tbl_order_detail.product_id', $product_ids)
                ->whereNotIn('tbl_order.customer_id', [Session::get('customer_id')])
                ->get();
            foreach ($order_with_product_ids as $key => $v_order_with_product_ids) {
                // Lấy tất cả các order_id của khách hàng khác đã mua sản phẩm cùng loại
                $orders = DB::table('tbl_order')
                    ->where('customer_id', $v_order_with_product_ids->customer_id)
                    ->select('tbl_order.order_id')
                    ->get()
                    ->pluck('order_id')  // Chuyển đổi Collection thành mảng các order_id
                    ->toArray();

                $order_ids = array_merge($order_ids, $orders);  // Gộp tất cả các order_id vào mảng $order_ids
            }
            $RCM_product = DB::table('tbl_order_detail')
                ->whereIn('order_id', $order_ids)
                ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_order_detail.product_id')
                ->get()->unique('product_id')->values();

            
            
          
        }
  
        $active_promotion = DB::table('tbl_promotion')->where('promotion_status','Có')->get();
        
        $selling_products = DB::table('tbl_product')
        ->orderBy('quantity_sold', 'desc')
        ->take(10)
        ->get();
        
        if (isset($RCM_product)) {
            return view('pages.home')
                ->with('slider_short', $slider_short)
                ->with('slider_large', $slider_large)
                ->with('cart_detail', $cart_detail)
                ->with('category', $category)
                ->with('selling_products', $selling_products)
                ->with('brand', $brand)
                ->with('active_promotion', $active_promotion)
                ->with('RCM_product', $RCM_product)
                ->with('new_product', $new_product);
        } else {
            return view('pages.home')
                ->with('slider_short', $slider_short)
                ->with('slider_large', $slider_large)
                ->with('cart_detail', $cart_detail)
                ->with('active_promotion', $active_promotion)
                ->with('selling_products', $selling_products)
                ->with('category', $category)
                ->with('brand', $brand)
                ->with('new_product', $new_product);
        }


    }
    public function show_product_category($category_slug, Request $request)
    {
        Session::put('low_to_high', null);
        Session::put('high_to_low', null);
        Session::put('low_to_high_search', null);
        Session::put('high_to_low_search', null);
        $min_price = DB::table('tbl_product')->min(DB::raw('CAST(product_price AS UNSIGNED)')) - 100000;
        $max_price = DB::table('tbl_product')->max(DB::raw('CAST(product_price AS UNSIGNED)')) + 100000;
        Session::put('min_price', $min_price);
        Session::put('max_price', $max_price);

        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_slug', 'desc')->get();

        $category_name = Category::where('tbl_category_product.category_slug', $category_slug)
            ->limit(1)->get();
        $banner = DB::table('tbl_slider')->where('slider_option', 3)->where('slider_status', 1)->get();

        $filter_by = $request->filter_by;
        if ($filter_by) {
            switch ($filter_by) {
                case 'low_to_high':
                    Session::put('low_to_high_cat', 'low_to_high_cat');
                    Session::put('high_to_low_cat', null);
                    break;
                case 'high_to_low':
                    Session::put('high_to_low_cat', 'high_to_low_cat');
                    Session::put('low_to_high_cat', null);

                    break;
            }
        }

        if (Session::get('low_to_high_cat') != null) {
            $query = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
                ->where('tbl_category_product.category_slug', $category_slug);
            $product_by_category = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'asc')->paginate(2);

            return view('pages.show_product_category')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)
                ->with('brand', $brand)
                ->with('category_name', $category_name)
                ->with('product_by_category', $product_by_category);
        } elseif (Session::get('high_to_low_cat') != null) {
            $query = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
                ->where('tbl_category_product.category_slug', $category_slug);
            $product_by_category = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'desc')->paginate(2);

            return view('pages.show_product_category')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)
                ->with('brand', $brand)
                ->with('category_name', $category_name)
                ->with('product_by_category', $product_by_category);
        } else {
            $query = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
                ->where('tbl_category_product.category_slug', $category_slug);
            $product_by_category = $query->paginate(2);

            return view('pages.show_product_category')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)
                ->with('brand', $brand)
                ->with('category_name', $category_name)
                ->with('product_by_category', $product_by_category);
        }
    }
    public function show_product_brand($brand_slug, Request $request)
    {
        Session::put('low_to_high_cat', null);
        Session::put('high_to_low_cat', null);
        Session::put('low_to_high_search', null);
        Session::put('high_to_low_search', null);
        $min_price = DB::table('tbl_product')->min(DB::raw('CAST(product_price AS UNSIGNED)')) - 100000;
        $max_price = DB::table('tbl_product')->max(DB::raw('CAST(product_price AS UNSIGNED)')) + 100000;
        Session::put('min_price', $min_price);
        Session::put('max_price', $max_price);
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_slug', 'desc')->get();

        $brand_name = Brand::where('tbl_brand.brand_slug', $brand_slug)
            ->limit(1)->get();
        $banner = DB::table('tbl_slider')->where('slider_option', 3)->where('slider_status', 1)->get();

        $filter_by = $request->filter_by;
        if ($filter_by) {
            switch ($filter_by) {
                case 'low_to_high':
                    // $query = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'asc')->paginate(2);
                    Session::put('low_to_high', 'low_to_high');
                    Session::put('high_to_low', null);
                    break;
                case 'high_to_low':
                    // $query = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'desc')->paginate(2);
                    Session::put('high_to_low', 'high_to_low');
                    Session::put('low_to_high', null);
                    break;

            }

        }
        if (Session::get('low_to_high') != null) {
            $query = DB::table('tbl_product')
                ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
                ->where('tbl_brand.brand_slug', $brand_slug);
            $product_by_brand = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'asc')->paginate(2);

            return view('pages.show_product_brand')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)->with('brand_name', $brand_name)
                ->with('brand', $brand)->with('product_by_brand', $product_by_brand);
        } elseif (Session::get('high_to_low') != null) {
            $query = DB::table('tbl_product')
                ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
                ->where('tbl_brand.brand_slug', $brand_slug);
            $product_by_brand = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'desc')->paginate(2);

            return view('pages.show_product_brand')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)->with('brand_name', $brand_name)
                ->with('brand', $brand)->with('product_by_brand', $product_by_brand);
        } else {
            $query = DB::table('tbl_product')
                ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
                ->where('tbl_brand.brand_slug', $brand_slug);
            $product_by_brand = $query->paginate(2);

            return view('pages.show_product_brand')
                ->with('banner', $banner)
                ->with('category', $category)
                ->with('cart_detail', $cart_detail)->with('brand_name', $brand_name)
                ->with('brand', $brand)->with('product_by_brand', $product_by_brand);
        }


    }
    public function product_detail(Request $request, $product_slug)
    {
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_slug', 'desc')->get();
        $product_detail = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->where('tbl_product.product_slug', $product_slug)->get();
        $total_start = 0;
        $count = 0;

        foreach ($product_detail as $key => $v_product_detail) {

            $rating = DB::table('tbl_comment')
                ->join('tbl_rating', 'tbl_rating.comment_id', '=', 'tbl_comment.comment_id')
                ->where('product_id', $v_product_detail->product_id)
                ->orderBy('tbl_comment.comment_id', 'desc')->paginate(5);
            if ($rating->isEmpty()) {

            } else {
                foreach ($rating as $key => $v_rating) {
                    // Kiểm tra dữ liệu từng đánh giá
                    $count++;
                    $total_start += ($v_rating->rating_start);


                }
            }


        }
        if ($count == 0) {
            $mean = round($total_start);
        } else {
            $mean = round($total_start / $count);
        }

        $category_id = '';
        foreach ($product_detail as $key => $value) {
            $category_id = $value->category_id;
            $url = $request->url();
        }

        $related_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->where('tbl_category_product.category_id', $category_id)->whereNotIn('tbl_product.product_slug', [$product_slug])->get();
        $detail = DB::table('tbl_product')->where('product_slug', $product_slug)->first();
        $image_detail = DB::table('tbl_product_image')->where('product_id', $detail->product_id)->get();
        return view('pages.product_detail')
            ->with('mean', $mean)
            ->with('count', $count)
            ->with('url', $url)
            ->with('image_detail', $image_detail)
            ->with('cart_detail', $cart_detail)
            ->with('category', $category)
            ->with('brand', $brand)
            ->with('product_detail', $product_detail)
            ->with('rating', $rating)

            ->with('related_product', $related_product);

    }
    public function login_register()
    {
        return view('pages.login_register');
    }
    public function add_customer(Request $request)
    {
        $check = Customer::where('customer_email', $request->register_email)->first();
        if ($check) {
            Session::put('message-register', 'Tài khoản email đã tồn tại');
            return Redirect()->back();
        } else {
            $data = array();
            $data['customer_name'] = $request->register_name;
            $data['customer_email'] = $request->register_email;
            $data['customer_password'] = md5($request->register_password);
            $data['customer_phone'] = $request->register_phone;

            $customer_id = Customer::insertGetId($data);
            Session::put('customer_id', $customer_id);
            Session::put('customer_name', $request->customer_name);
            Session::put('customer_email', $request->customer_email);
            return Redirect::to('/');
        }


    }
    public function logout()
    {
        Session::flush();
        return Redirect::to('/login-register');
    }
    public function login(Request $request)
    {
        $email = $request->login_email;
        $password = md5($request->login_password);

        $result = Customer::where('customer_email', $email)->where('customer_password', $password)->first();
        if ($result == null) {
            Session::put('message', 'Tài khoản hoặc mặt khẩu không đúng');
            return Redirect::to('/login-register');
        } else {
            Session::put('customer_id', $result->customer_id);
            Session::put('customer_name', $result->customer_name);
            Session::put('customer_email', $result->customer_email);
            Session::put('address_id', $result->address_id);
            return Redirect::to('/');
        }


    }
    public function my_account($customer_id)
    {
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $customer = Customer::where('customer_id', $customer_id)->first();
        return view('pages.customer.my_account')
            ->with('cart_detail', $cart_detail)
            ->with('category', $category)
            ->with('brand', $brand)
            ->with('customer', $customer);
    }
    public function save_name(Request $request, $customer_id)
    {
        $customer_name = $request->input('customer_name');
        Customer::where('customer_id', $customer_id)->update(['customer_name' => $customer_name]);
        Session::put('customer_name', $customer_name);
        return Redirect()->back();
    }
    public function change_password(Request $request, $customer_id)
    {
        $old_password = md5($request->input('old_password'));
        $new_password = md5($request->input('new_password'));
        $DB_pass = Customer::where('customer_id', $customer_id)->first();
        if ($old_password == $DB_pass->customer_password) {
            Customer::where('customer_id', $customer_id)->update(['customer_password' => $new_password]);
            Session::put('message-succes', 'Đổi mật khẩu thành công');
        } else {
            Session::put('message', 'Mật khẩu cũ không đúng');
        }

        return Redirect()->back();
    }
    public function account_detail($customer_id)
    {

        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        $address = DB::table('tbl_address')
            ->join('tbl_tinhthanhpho', 'tbl_tinhthanhpho.matp', '=', 'tbl_address.matp')
            ->join('tbl_quanhuyen', 'tbl_quanhuyen.maqh', '=', 'tbl_address.maqh')
            ->join('tbl_xaphuongthitran', 'tbl_xaphuongthitran.xaid', '=', 'tbl_address.xaid')
            ->join('tbl_customers', 'tbl_customers.address_id', '=', 'tbl_address.address_id')
            ->where('tbl_customers.customer_id', $customer_id)
            ->get();
        $customer = DB::table('tbl_customers')->where('customer_id', $customer_id)->first();
        return view('pages.customer.account_detail')
            ->with('customer', $customer)
            ->with('cart_detail', $cart_detail)
            ->with('category', $category)
            ->with('brand', $brand)
            ->with('address', $address);
    }
    public function filter_price($start_price, $end_price)
    {
        $min_price = DB::table('tbl_product')->min(DB::raw('CAST(product_price AS UNSIGNED)')) - 100000;
        $max_price = DB::table('tbl_product')->max(DB::raw('CAST(product_price AS UNSIGNED)')) + 100000;
        Session::put('min_price', $min_price);
        Session::put('max_price', $max_price);
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        $filter_price = DB::table('tbl_product')
            ->whereBetween(DB::raw('CAST(product_price AS UNSIGNED)'), [$start_price, $end_price])
            ->orderBy('product_price', 'asc')
            ->paginate(2);

        return view('pages.filter_price')->with(compact('cart_detail', 'category', 'brand', 'filter_price'));
    }

    public function search($search, Request $request)
    {
        $min_price = DB::table('tbl_product')->min(DB::raw('CAST(product_price AS UNSIGNED)')) - 100000;
        $max_price = DB::table('tbl_product')->max(DB::raw('CAST(product_price AS UNSIGNED)')) + 100000;
        Session::put('min_price', $min_price);
        Session::put('max_price', $max_price);
        Session::put('low_to_high', null);
        Session::put('high_to_low', null);
        Session::put('low_to_high_cat', null);
        Session::put('high_to_low_cat', null);
        $searched = $request->searched;
        $cart_detail = DB::table('tbl_cart_detail')->where('customer_id', Session::get('customer_id'))->get();
        $category = Category::where('category_status', '1')->where('category_parent','0')->orderBy('category_slug', 'desc')->get();
        $brand = Brand::where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
        Session::put('searched', $searched);


        $filter_by = $request->filter_by;
        if ($filter_by) {
            switch ($filter_by) {
                case 'low_to_high':
                    Session::put('low_to_high_search', 'low_to_high_search');
                    Session::put('high_to_low_search', null);
                    break;
                case 'high_to_low':
                    Session::put('high_to_low_search', 'high_to_low_search');
                    Session::put('low_to_high_search', null);

                    break;

            }

        }
        if (Session::get('low_to_high_search') != null) {
            $query = DB::table('tbl_product')->where('product_name', 'like', '%' . $search . '%');
            $search_product = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'asc')
                ->paginate(2);

            return view('pages.search')
                ->with('cart_detail', $cart_detail)
                ->with('category', $category)->with('brand', $brand)->with('search_product', $search_product);
        } elseif (Session::get('high_to_low_search') != null) {
            $query = DB::table('tbl_product')->where('product_name', 'like', '%' . $search . '%');
            $search_product = $query->orderBy(DB::raw('CAST(product_price AS DECIMAL(10,2))'), 'desc')
                ->paginate(2);

            return view('pages.search')
                ->with('cart_detail', $cart_detail)
                ->with('category', $category)->with('brand', $brand)->with('search_product', $search_product);
        } else {
            $query = DB::table('tbl_product')->where('product_name', 'like', '%' . $search . '%');
            $search_product = $query->paginate(2)->appends($request->except('page'));

            return view('pages.search')
                ->with('cart_detail', $cart_detail)
                ->with('category', $category)->with('brand', $brand)->with('search_product', $search_product);
        }

    }
    public function autocomplete_search(Request $request)
    {
        $data = $request->all();
        if ($data['query']) {
            $product = DB::table('tbl_product')->where('product_name', 'like', '%' . $data['query'] . '%')->get();
            $output = '
                <ul class = "dropdown-menu" style="display:block;width:100%;">';
            foreach ($product as $key => $value) {
                $productUrl = url('/san-pham/' . $value->product_slug);
                $output .= '
                        <li class="text_complete" style="padding:0.5rem;font-size: 1.3rem;"><a class="dropdown-item" href="' . $productUrl . '">' . $value->product_name . '</a></li>
                    ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function rating(Request $request)
    {
        $data = array();
        $data['comment'] = $request->rating_comment;
        $data['product_id'] = $request->product_id;
        $data['customer_id'] = Session::get('customer_id');

        $comment_id = DB::table('tbl_comment')->insertGetId($data);
        $data_rating = array();
        $data_rating['rating_start'] = $request->rating_start;
        $data_rating['comment_id'] = $comment_id;
        DB::table('tbl_rating')->insert($data_rating);

        return Redirect()->back();
    }

}
