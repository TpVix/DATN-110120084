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


class ReviewController extends Controller
{
    public function list_review (){
        $list_review = DB::table('tbl_comment')
        ->leftJoin('tbl_rating', 'tbl_rating.comment_id', '=', 'tbl_comment.comment_id')->get();
        return view('admin.review.list_review')->with(compact('list_review'));
    }
}
