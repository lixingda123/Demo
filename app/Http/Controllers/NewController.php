<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class NewController extends Controller
{
    //新闻查询
    public function New(Request $request){
    	$search=$request->input()??'';
    	$where=[];
    	$page=$search['page']??'1';
    	$sea=$search['search']??'';
    	$key=$sea.$page;
    	// dd($key);
    	$data=Cache::get($key);
    	if(!$data){
    		echo "数据库";
    		if($search!=''){
    			$where[]=['essay_title','like',"%".$sea."%"];
    		}
		    $data=DB::table('shop_essay')->where($where)->paginate(2);
		    		// $da=DB::table('shop_essay')->where($where)->get();
		    Cache::put($key,$data,1);
    	}
    		// Cache::flush();
    	return view('new/new',compact('data','search'));
    }
}
