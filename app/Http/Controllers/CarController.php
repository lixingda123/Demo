<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
	//购物车
    public function car(){
        $data=DB::select("select * from shop_goods join shop_cart on shop_goods.goods_id=shop_cart.goods_id and status=1");
        $count=count($data);
    	return view('car.car',compact('data','count'));
    }
    //加入购物车
    public function AddCar(Request $request){
        if(!session('user')){
            $arr=[
                'font'=>'请登录',
                'code'=>3
            ];
            return json_encode($arr);
        }else{
            $data=$request->all();
            // dd($data);
            unset($data['_token']);
            $goods_id=$data['goods_id'];
            $where=[
                'goods_id'=>$goods_id
            ];
            // dd($where);
            $goods_model=new \App\Good;
            $goodsInfo=$goods_model::where($where)->first();
            // dd($goodsInfo);
            if(!$goodsInfo){
                $arr=[
                'font'=>'请选择正确的商品',
                'code'=>2
                ];
            return json_encode($arr);exit;
            }else{
                 $reg="/^\d{1,}$/";

                if(!preg_match($reg,$data['buy_num'])){
                     $arr=[
                            'font'=>'请选择正确的购买数量',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else if($data['buy_num']>$goodsInfo['goods_inventory']){
                    $arr=[
                            'font'=>'库存不足',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else if($data['buy_num']<1){
                    $arr=[
                            'font'=>'至少选择一件商品',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else{
                    $cart_model=new \App\Car;
                    $where=[
                        'user_id'=>session('user.user_id'),
                        'goods_id'=>$data['goods_id'],
                        'status'=>1
                    ];
                    $carInfo=$cart_model::where($where)->first();
                    if($carInfo){
                        if(($carInfo['buy_num']+$data['buy_num'])>$goodsInfo['goods_inventory']){
                            $arr=[
                                'font'=>'库存不足',
                                'code'=>2
                            ];
                         return json_encode($arr);exit;
                        }else{
                            $cart=$cart_model->find($carInfo['id']);
                            $cart->buy_num=$carInfo['buy_num']+$data['buy_num'];
                            $res=$cart->save();
                            if($res){
                                $arr=[
                                    'font'=>'加入购物车成功',
                                    'code'=>1
                                ];
                                return json_encode($arr);exit;
                            }else{
                                $arr=[
                                    'font'=>'加入购物车失败',
                                    'code'=>2
                                ];
                                return json_encode($arr);exit;
                            }
                        }  
                    }else{
                        foreach($data as $k=>$v){
                            $cart_model->$k=$v;
                        }
                        $cart_model->user_id=session('user.user_id');
                        $res=$cart_model->save();
                        if($res){
                            $arr=[
                                'font'=>'加入购物车成功',
                                'code'=>1
                            ];
                            return json_encode($arr);exit;
                        }else{
                            $arr=[
                                'font'=>'加入购物车失败',
                                'code'=>2
                            ];
                            return json_encode($arr);exit;
                        }
                    }
                    
                }

            }
        }
    }
    //修改商品数量
    public function updNum(Request $request){
        $data=$request->all();
        unset($data['_token']);
        $goods_id=$data['goods_id'];
            $where=[
                'goods_id'=>$goods_id
            ];
        // dd($where);
        $goods_model=new \App\Good;
        $goodsInfo=$goods_model::where($where)->first();
        $reg="/^\d{1,}$/";
        if(!$goodsInfo){
            $arr=[
                'font'=>'请选择正确的商品',
                'code'=>2
            ];
            return json_encode($arr);exit;
        }else{
            if(!preg_match($reg,$data['buy_num'])){
                     $arr=[
                            'font'=>'请选择正确的购买数量',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else if($data['buy_num']>$goodsInfo['goods_inventory']){
                    $arr=[
                            'font'=>'库存不足',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else if($data['buy_num']<1){
                    $arr=[
                            'font'=>'至少选择一件商品',
                            'code'=>2
                        ];
                    return json_encode($arr);exit;
                }else{
                    
                    $cart_model=\App\Car::find($data['id']);
                    $cart_model->buy_num=$data['buy_num'];
                    $res=$cart_model->save();
                    $Info=DB::table('shop_cart')
                            ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                            ->where('shop_cart.id',$data['id'])
                            ->first();
                    // dd($Info);
                    if($res){
                        $arr=[
                            'font'=>'操作成功',
                            'code'=>1,
                            'price'=>$Info->goods_price*$Info->buy_num
                        ];
                        return json_encode($arr);exit;
                    }else{
                        $arr=[
                            'font'=>'操作失败',
                            'code'=>2
                        ];
                        return json_encode($arr);exit;
                    }
                }
        }            
    }
    //总价格
    public function total(Request $request){
        $id=$request->id??'';
        $price=0;
        if($id){
            
             $data=DB::select("select * from shop_goods join shop_cart on shop_goods.goods_id=shop_cart.goods_id and status=1 and id in(".$id.")");
             // var_dump($data);die;
            foreach($data as $k=>$v){
               $price=$price+$v->goods_price*$v->buy_num;
            }
            return $price;
        }else{
            return $price;
        } 
    }
    //购物车结算
    public function pay(Request $request){
        $cart_id=$request->id??'';
        if(!session('user')){
            return redirect('/index/login');
        }else if(!$cart_id){
            return redirect('/index/car');
        }else{
            $cart_id=explode(',',$cart_id);
            $price=0;
            $data=DB::table('shop_cart')
                            ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                            ->where('shop_cart.status',1)
                            ->wherein('shop_cart.id',$cart_id)
                            ->get();
            foreach($data as $k=>$v){
                $price=$price+$v->goods_price*$v->buy_num;
            }
            //查询收货地址
            $where=[
                'user_id'=>session('user.user_id')
            ];
            $address=DB::table('shop_address')->where($where)->get();
            $model=new \App\Area;
            foreach($address as $k=>$v){
                        $address[$k]->province=$model->where(['id'=>$v->province])->first()['name'];
                        $address[$k]->city=$model->where(['id'=>$v->city])->first()['name'];
                        $address[$k]->area=$model->where(['id'=>$v->area])->first()['name'];
            }

           return view('car.pay',compact('data','price','address')); 
        }   
    }
    //购物车结算验证
    public function payAdd(Request $request){
        $cart_id=$request->id??'';
        if(!session('user')){
            $arr=[
                'font'=>'请登录',
                'code'=>3
            ];
            return json_encode($arr);exit;
        }else if(!$cart_id){
            $arr=[
                'font'=>'请选择商品进行结算',
                'code'=>2
            ];
            return json_encode($arr);exit;
        }else{
            return json_encode(['code'=>1]);exit;
        }  
    }
    //提交订单执行
    public function successAdd(Request $request){
        $data=$request->all();
        unset($data['_token']);
        //生成订单号
        $order_no=time().rand(10000000,99999999);
        //存入订单表
        $goods_id=explode(',',$data['goods_id']);
        $goodsInfo=DB::table('shop_cart')
                            ->join('shop_goods','shop_goods.goods_id','=','shop_cart.goods_id')
                            ->where(['shop_cart.user_id'=>session('user.user_id'),'shop_cart.status'=>1])
                            ->wherein('shop_goods.goods_id',$goods_id)
                            ->get();
        $order_amout=0;
        foreach($goodsInfo as $k=>$v){
            $order_amout=$order_amout+$v->buy_num*$v->goods_price;
        }
        $arr=[
            'order_no'=>$order_no,
            'user_id'=>session('user.user_id'),
            'pay_way'=>$data['pay_way'],
            'order_amout'=>$order_amout
        ];
        $res=DB::table('shop_order')->insert($arr);
        $order_id=DB::getPdo()->lastInsertId($res);
        //存入商品收货地址表
            //查询收货地址
            $addModel=new \App\Address;
            $addInfo=$addModel::where('address_id',$data['address_id'])->first()->toArray();
            unset($addInfo['created_at']);
            unset($addInfo['updated_at']);
            unset($addInfo['user_id']);
            unset($addInfo['is_default']);
            unset($addInfo['address_id']);
        $orderAddress=new \App\OrAdd;
        foreach($addInfo as $k=>$v){
                $orderAddress->$k=$v;
        }
        $orderAddress->order_id=$order_id;
        $orderAddress->user_id=session('user.user_id');
        $res=$orderAddress->save();
        //存入商品详情
        $detail_model=new \App\Detail;
        $detail_model->order_id=$order_id;
        $detail_model->order_no=$order_no;
        
        // dd($goods_id);
        $detail_model->user_id=session('user.user_id');
            //查询结算的商品
            $goods_model=new \App\Good;
            
            foreach($goodsInfo as $k=>$v){
                $where=[
                    'user_id'=>session('user.user_id'),
                    'order_no'=>$order_no,
                    'order_id'=>$order_id,
                    'goods_id'=>$v->goods_id,
                    'buy_num'=>$v->buy_num,
                    'goods_price'=>$v->goods_price,
                    'goods_name'=>$v->goods_name,
                    'goods_img'=>$v->goods_img
                ];
                $res=DB::table('shop_order_detail')->insert($where);
                if($res){
                    //删除购物车
                    $re=DB::table('shop_cart')->where('id',$v->id)->update(['status'=>2]);
                    //商品库存减少
                    $r=DB::table('shop_goods')->where('goods_id',$v->goods_id)->update(['goods_inventory'=>$v->goods_inventory-$v->buy_num]);
                    if($r){
                        $arr=[
                            'font'=>'订单提交成功',
                            'code'=>1
                        ];
                        return json_encode($arr);
                    }else{
                        $arr=[
                            'font'=>'订单提交失败',
                            'code'=>2
                        ];
                        return json_encode($arr);
                    }
                }
            }
        
    }
    //提交订单
    public function success(Request $request){
        $goods_id=$request->goods_id??'';
        $reg="/^[0-9,]{1,}$/";
        if(!preg_match($reg, $goods_id)){
            return redirect('/index/index');
        }
       $goods_id=explode(',',$goods_id);
        $Info=DB::table('shop_order')
                    ->join('shop_order_address','shop_order.order_id','=','shop_order_address.order_id')
                    ->join('shop_order_detail','shop_order.order_id','=','shop_order_detail.order_id')
                    ->where('shop_order.user_id',session('user.user_id'))
                    ->wherein('shop_order_detail.goods_id',$goods_id)
                    ->first();
        // dd($Info);
    	return view('car.success',compact('Info'));
    }
    //支付页面
    public function ali_pay($order_no){
        if(!$order_no){
            return redirect('index/car')->with('message','没有此订单信息');
        }
        $order=DB::table('shop_order')->select(['order_no','order_amout'])->where('order_no',$order_no)->first();
        if($order->order_amout<=0){
           return redirect('index/car')->with('message','请选择正确的订单');
        }
        // DIRECTORY_SEPARATOR
        require_once app_path('/libs/alipay/wappay/service/AlipayTradeService.php');
        require_once app_path('/libs/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        // require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';
        if (!empty($order_no)&& trim($order_no)!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $order_no;

            //订单名称，必填
            $subject = '王萎龙';

            //付款金额，必填
            $total_amount = $order->order_amout;

            //商品描述，可空
            $body = 'verey good';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService(config('alipay'));
            $result=$payResponse->wapPay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));

            return ;








            // require_once app_path('/libs/alipay/pagepay/service/AlipayTradeService.php');
            // require_once app_path('/libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');


            // //商户订单号，商户网站订单系统中唯一订单号，必填
            // $out_trade_no = trim($order_no);

            // //订单名称，必填
            // $subject = "1809a测试";

            // //付款金额，必填
            // $total_amount = trim($order->order_amout);

            // //商品描述，可空
            // $body = "1809a测试";

            // //构造参数
            // $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            // $payRequestBuilder->setBody($body);
            // $payRequestBuilder->setSubject($subject);
            // $payRequestBuilder->setTotalAmount($total_amount);
            // $payRequestBuilder->setOutTradeNo($out_trade_no);

            // $aop = new \AlipayTradeService(config('alipay'));

            // /**
            //  * pagePay 电脑网站支付请求
            //  * @param $builder 业务参数，使用buildmodel中的对象生成。
            //  * @param $return_url 同步跳转地址，公网可以访问
            //  * @param $notify_url 异步通知地址，公网可以访问
            //  * @return $response 支付宝返回的信息
            // */
            // $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));

            // //输出表单
            // var_dump($response);
        }
    
    }
    public function returnpay(){
        $res=DB::table('shop_order')->where(["order_no"=>$_GET['out_trade_no'],'order_amout'=>$_GET['total_amount']])->first();
        // dd($res);
        if(!$res){
            return redirect('/index/car')->with('message','订单信息不存在');
        }else{
             require_once app_path('/libs/alipay/wappay/service/AlipayTradeService.php');
            $arr=$_GET;
            $alipaySevice = new \AlipayTradeService(config('alipay')); 
            $result = $alipaySevice->check($arr);
            if($result){
                return redirect('/user/order');
            }
        }
       
    }
    public function notifypay(){
        require_once app_path('/libs/alipay/wappay/service/AlipayTradeService.php');
        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService(config('alipay')); 
        // $alipaySevice->writeLog(var_export($_POST,true));
        // Log::emergency('11252');
        Log::channel('notify')->info(json_encode($arr));
        $result = $alipaySevice->check($arr);
        if($result) {
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序            
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                
            echo "success";     //请不要修改或删除
                
        }else {
            //验证失败
            echo "fail";    //请不要修改或删除

        }
    }
}