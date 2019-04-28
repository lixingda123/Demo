<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
	//用户中心
    public function user(){
    	return view('user.user');
    }
    //订单
    public function order(){
        $data=DB::table('shop_order_detail')
                ->join('shop_order','shop_order.order_no','=','shop_order_detail.order_no')
                ->join('shop_order_address','shop_order_detail.order_id','=','shop_order_address.order_id')
                ->where('shop_order_detail.user_id',session('user.user_id'))
                ->get();
        // dd($data);
    	return view('user.order',compact('data'));
    }
    //优惠券
    public function quan(){
    	return view('user.quan');
    }
    //收货地址
    public function address(){
        $data=DB::table('shop_address')->where('user_id',session('user.user_id'))->get();
        $Info=DB::table('shop_area')->get();
        foreach($data as $k=>$v){
           $data[$k]->province=DB::table('shop_area')->where('id',$v->province)->first()->name;
           $data[$k]->city=DB::table('shop_area')->where('id',$v->city)->first()->name;
           $data[$k]->area=DB::table('shop_area')->where('id',$v->area)->first()->name;
        }

    	return view('user.address',compact('data'));
    }
    //收货地址添加
    public function addressAdd(Request $request){
       $address= $this->area($request);
    	return view('user.addressAdd',compact('address'));
    }
    //收货地址添加执行
    public function addressAddDo(Request $request){
        $data=$request->all();
        unset($data['_token']);
        $model=new \App\Address;
        if($data['is_default']=='1'){
            $res=DB::table('shop_address')->where('user_id',session('user.user_id'))->update(['is_default'=>2]);
        }
            foreach($data as $k=>$v){
                $model->$k=$v;
            }
            $model->user_id=session('user.user_id');
            // dd($model);
            $res=$model->save();
            if($res){
                $arr=[
                    'font'=>'添加成功',
                    'code'=>1
                ];
                return json_encode($arr);
            }else{
                $arr=[
                    'font'=>'添加失败',
                    'code'=>2
                ];
                return json_encode($arr);
            }
        
    }
    //地址
    public function area(Request $request){
        $id=$request->id??'0';
        $where=[
            'pid'=>$id
        ];
        $model=new \App\Area;
        $data=$model::where($where)->get();
        return $data;
    }
    //三级联动获取地址
    public function getarea(Request $request){
        $area= $this->area($request);
        $arr=[
            'area'=>$area,
            'code'=>1
        ];
        echo json_encode($arr);
    }
    //收藏
    public function save(){
    	return view('user.save');
    }
    //浏览历史
    public function history(){
    	return view('user.history');
    }
    //提现
    public function withdraw(){
    	return view('user.withdraw');
    }
}
