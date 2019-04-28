<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Mail;
class GoodsController extends Controller
{

    public function addgoods(Request $request){
        //dd(1111);
        $post=$request->only(['goods_name','goods_img','goods_num','goods_desc']);
        //dd($post);
        if($request->hasFile('goods_img')){
            $post['goods_img']=$this->upload($request,'goods_img');
        }
        //查询构造器
        $res=DB::table('goods')->insert($post);
        dd($res);
        if($res){
            return "<script>alert('成功');location.href='/goods/goodslist'</script>";
        }
    }
}