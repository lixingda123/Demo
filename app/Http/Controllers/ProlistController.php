<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class ProlistController extends Controller
{
   	//所有商品
    public function prolist(Request $request){
    	$model=new \App\ShopGood;
        $where=[];
        if($request->search??''){
            $where[]=['goods_name','like',"%".$request->search."%"];
        }
    	$data=$model::where($where)->get();
    	return view('prolist.prolist',compact('data'));
    }
    //重新获取商品
    public function getNewGoods(Request $request){
        $model=new \App\ShopGood;
        $status=$request->status??'';
        $where=[];
        if($status==1){
            $where['goods_new']=1;
        }else if($status==2){
            $where['goods_best']=1;
        }
        if($request->val??''){
            $where[]=['goods_name','like',"%".$request->val."%"];
        }
        if($status==3){
            $data=$model::where($where)->orderBy('goods_price',$request->order)->get();
        }else{
            $data=$model::where($where)->get();
        }

        // dd($where);
        
        return view('prolist.replace',compact('data'));
    }
    //商品详情
    public function proinfo($id){
    	$model=new \App\ShopGood;
        $where=[
            'goods_id'=>$id
        ];
    	$data=$model::where($where)->first();
    	
    	$showimg=explode('|',$data['goods_showimg']);
    	unset($showimg[0]);
    	return view('prolist.proinfo',compact('data','showimg'));
    }
    //发送邮件
    public function sendemail(){
        $flag=Mail::send('index.email',['data'=>'1111111'],function($message){
            $to="1339013937@qq.com";
            $message->to($to)->subject('验证码');
        });
    }
}
