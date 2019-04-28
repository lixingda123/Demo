<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//主页
Route::get('index/index',"IndexController@index");
Route::match(['get','post'],'index/login',"IndexController@login");//登录
Route::match(['get','post'],'index/quit',"IndexController@quit");//退出
Route::match(['get','post'],'index/reg',"IndexController@reg");//注册
Route::match(['get','post'],'index/loginAdd',"IndexController@loginAdd");//登录执行
Route::match(['get','post'],'index/code',"IndexController@code");//发送验证码
Route::post('index/tel',"IndexController@tel");//发送短信验证码
//发送邮箱验证码
Route::any('/index/email','IndexController@email');

Route::post('index/regAdd',"IndexController@regAdd");//注册执行
Route::match(['get','post'],'index/sendemail',"ProlistController@sendemail");//发送邮件
//所有商品
Route::match(['get','post'],'index/prolist','ProlistController@prolist');
Route::match(['get','post'],'index/getNewGoods','ProlistController@getNewGoods');//重新获取商品
Route::match(['get','post'],'prolist/proinfo/{id}','ProlistController@proinfo');//商品详情

//购物车
Route::get('index/car','CarController@car')->middleware('login');
Route::match(['get','post'],'index/AddCar',"CarController@AddCar");//加入购物车
Route::match(['get','post'],'index/updNum',"CarController@updNum");//修改商品数量
Route::match(['get','post'],'index/total',"CarController@total");//总价格
Route::match(['get','post'],'index/pay',"CarController@pay");//结算
Route::match(['get','post'],'index/payAdd',"CarController@payAdd");//结算
Route::match(['get','post'],'index/success',"CarController@success");//提交订单
Route::match(['get','post'],'index/successAdd',"CarController@successAdd");//提交执行
Route::match(['get','post'],'index/pay/{order_no}',"CarController@ali_pay");//支付页面
Route::match(['get','post'],'index/returnpay',"CarController@returnpay");//支付成功跳转
Route::match(['get','post'],'index/notifypay',"CarController@notifypay");//支付成功异步通知

//用户
Route::get('index/user','UserController@user')->middleware('login');
Route::match(['get','post'],'user/order',"UserController@order");//订单
Route::match(['get','post'],'index/quan',"UserController@quan");//优惠券
Route::match(['get','post'],'index/address',"UserController@address");//收货地址
Route::match(['get','post'],'index/getarea',"UserController@getarea");//三级联动获取地址
Route::match(['get','post'],'index/area',"UserController@area");//地址查询
Route::match(['get','post'],'index/addressAddDo',"UserController@addressAddDo");//收货地址添加

Route::match(['get','post'],'index/addressAdd',"UserController@addressAdd");//收货地址添加
Route::match(['get','post'],'user/save',"UserController@save");//收藏
Route::match(['get','post'],'user/history',"UserController@history");//浏览历史
Route::match(['get','post'],'user/withdraw',"UserController@withdraw");//提现


//新闻
Route::match(['get','post'],'new/new',"NewController@new");//新闻

//
//Route::get('goods',"GoodsController@goods");
//Route::get('goods/goodslist',"GoodsController@goodslist");

