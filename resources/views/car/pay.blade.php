<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>三级分销</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    
    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/response.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond./js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
       <tr>
        <td class="dingimg" width="75%" colspan="2"><a href="/index/addressAdd">新增收货地</a>址</td>
        <td align="right"><a href="/index/addressAdd"><img src="/images/jian-new.png" /></a></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">
          <select style="width:500px" id="address_id">
            <option value=''>--请选择收货地址--</option>
            @foreach($address as $k=>$v)
            @if($v->is_default==1)
            <option value="{{$v->address_id}}" selected>
              收货人：{{$v->name}}&nbsp&nbsp&nbsp&nbsp地址:{{$v->province}}-{{$v->city}}-{{$v->area}}--{{$v->address}}</option>
            @else
            <option value="{{$v->address_id}}">
              收货人：{{$v->name}}&nbsp&nbsp&nbsp&nbsp地址:{{$v->province}}-{{$v->city}}-{{$v->area}}--{{$v->address}}</option>
            @endif
            @endforeach
          </select>
        </td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3" value>
          <select style="width:500px" id="pay_way">
            <option value=''>--请选择支付方式--</option>
            <option value="1">支付宝</option>
          </select>
        </td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
       @foreach($data as $k=>$v)
       <tr goods_id="{{$v->goods_id}}" class="aid">
        <td class="dingimg" width="15%"><img src="/images/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：{{$v->created_at}}</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_num}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->buy_num*$v->goods_price}}</strong></th>
       </tr>
       @endforeach
       
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$price}}</strong></td>
       </tr>
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr> -->
       <!-- <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥20.80</strong></td>
       </tr> -->
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$price}}</strong></td>
       <td width="40%"><a id="sub" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
    <script type="text/javascript" src="/layui/layui.js"></script>
    <!--jq加减-->
    <script src="/js/jquery.spinner.js"></script>
   <script>
	$('.spinnerExample').spinner({});
	</script>
  </body>
</html>
<script type="text/javascript">
  $(function(){
    layui.use('layer',function(){
      var layer=layui.layer;
      var _token=$('#token').val();
      //订单提交
      $('#sub').click(function(){
        var address_id=$('#address_id').val();
        var pay_way=$('#pay_way').val();
        var aid=$('.aid');
        var goods_id='';
        aid.each(function(index){
          goods_id+=$(this).attr('goods_id')+',';
        })
        goods_id=goods_id.substr(0,goods_id.length-1);
        if(address_id==''){
          layer.msg('请选择收货地址',{icon:2});
          return false;
        }else if(pay_way==''){
          layer.msg('请选择支付方式',{icon:2});
          return false;
        }else if(goods_id==''){
          layer.msg('请选择商品提交订单',{icon:2});
          return false;
        }
        $.post(
          "/index/successAdd",
          {_token:_token,address_id:address_id,pay_way:pay_way,goods_id:goods_id},
          function(res){
            if(res.code==1){
              layer.msg(res.font,{icon:res.code,time:1500},function(){
                location.href="/index/success?goods_id="+goods_id;
              })
            }else{
              layer.msg(res.font,{icon:res.code});
            }
          },  
          'json'
        );

      })
    })
  })
</script>