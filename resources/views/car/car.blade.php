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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(/images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" id="all" name="1" /> 全选</a></td>
       </tr>
       @foreach($data as $k=>$v)
       <tr cart_id="{{$v->id}}" goods_id="{{$v->goods_id}}">
        <td width="4%"><input type="checkbox" name="1" class="check" /></td>
        <td class="dingimg" width="15%"><img src="/images/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：{{$v->created_at}}</time>
        </td>
        <td align="right">
          <button class="less">－</button> 
          <input type="text" value="{{$v->buy_num}}" style="width:30px" class="val"> 
          <button class="add">＋</button>
          <input type="hidden" class="num" name="" value="{{$v->goods_inventory}}">
        </td>
       </tr>
       <tr>
        <th colspan="4"><strong class="orange">¥{{$v->goods_price*$v->buy_num}}</strong></th>
       </tr>
       @endforeach
      </table>
     </div><!--dingdanlist/-->
     <!--dingdanlist/-->
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥ <span id="countPrice">0</span></strong></td>
       <td width="40%"><a href="javascript:;" id="settle" class="jiesuan">去结算</a></td>
      </tr>
      <input type="hidden" id="token" value="{{csrf_token()}}">
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
    <!--jq加减-->
    <script src="/js/jquery.spinner.js"></script>
    <script type="text/javascript" src="/layui/layui.js"></script>
   <script>
	$('.spinnerExample').spinner({});
	</script>
  </body>
</html>
<script type="text/javascript">
  $(function(){
    layui.use('layer',function(){
      var layer = layui.layer;
      _token=$('#token').val();
      //点击全选
      $('#all').click(function(){
        var status=$(this).prop('checked');
        $('.check').prop('checked',status);
        total();
      })
      //点击加号
      $('.add').click(function(){
        var _this=$(this);
        var cart_id=_this.parents('tr').attr('cart_id');
        var goods_id=_this.parents('tr').attr('goods_id');
        // alert(goods_id);
        var val=parseInt(_this.prev().val());
        var num=_this.next().val();
        val+=1;
        if(val>=num){
          _this.prev().val(num);
          _this.attr('disabled','true');
          _this.prev().prev().removeAttr('disabled');
        }else{
          _this.prev().val(val);
          _this.prev().prev().removeAttr('disabled');
        }
        val=parseInt(_this.prev().val());

        $.post(
          '/index/updNum',
          {_token:_token,id:cart_id,buy_num:val,goods_id:goods_id},
          function(res){
              layer.msg(res.font,{icon:res.code});
              if(res.code==1){
                _this.parents('tr').next().find("strong[class='orange']").text('¥'+res.price);
                total();
              }
          },
          'json'
        ) 
      })
      //点击减号
      $('.less').click(function(){
        var _this=$(this);
        var cart_id=_this.parents('tr').attr('cart_id');
        var goods_id=_this.parents('tr').attr('goods_id');
        var val=parseInt(_this.next().val());
        val-=1;
        if(val<=1){
          _this.next().val(1);
          _this.next().next().removeAttr('disabled');
          _this.attr('disabled','true');
        }else{
          _this.next().next().removeAttr('disabled');
          _this.next().val(val);
        }
        val=parseInt(_this.next().val());
        $.post(
          '/index/updNum',
          {_token:_token,id:cart_id,buy_num:val,goods_id:goods_id},
          function(res){
              layer.msg(res.font,{icon:res.code});
              if(res.code==1){
                _this.parents('tr').next().find("strong[class='orange']").text('¥'+res.price);
              }
          },
          'json'
        )
        total();
      })
      //输入框失去焦点
      $('.val').blur(function(){
        var _this=$(this);
        var cart_id=_this.parents('tr').attr('cart_id');
        var goods_id=_this.parents('tr').attr('goods_id');
        var val=parseInt(_this.val());
        var num=_this.next().next().val();
        var reg=/^\d{1,}$/;
        if(!reg.test(val)){
          _this.val(1);
        }else if(val<=1){
          _this.val(1)
        }else if(val>=num){
          _this.val(num);
        }
        val=parseInt(_this.val());
        $.post(
          '/index/updNum',
          {_token:_token,id:cart_id,buy_num:val,goods_id:goods_id},
          function(res){
              layer.msg(res.font,{icon:res.code});
              if(res.code==1){
                _this.parents('tr').next().find("strong[class='orange']").text('¥'+res.price);
              }
          },
          'json'
        )
        total();
      })
      //点击复选框
      $('.check').click(function(){
        total();
      })
      //点击去结算
      $('#settle').click(function(){
        var box=$('.check');
        var cart_id='';
        box.each(function(index){
          if($(this).prop('checked')==true){
            cart_id+=$(this).parents('tr').attr('cart_id')+',';
          }
        })
        cart_id=cart_id.substr(0,cart_id.length-1);
        
            $.post(
              "/index/payAdd",
              {_token:_token,id:cart_id},
              function(res){
                if(res.code==3){
                  layer.msg(res.font,{icon:res.code});
                  return false;
                }else if(res.code==2){
                  layer.msg(res.font,{icon:res.code});
                  return false;
                }else{
                  location.href="/index/pay?id="+cart_id;
                }  
              },
              'json'
            );
      })
      //获取总价钱
      function total(){
        var check=$('.check');
        var cart_id='';
        check.each(function(index){
          if($(this).prop('checked')==true){
            cart_id+=$(this).parents('tr').attr('cart_id')+',';
          }
        })
          cart_id=cart_id.substr(0,cart_id.length-1);
          $.post(
            "/index/total",
            {_token:_token,id:cart_id},
            function(res){
                $('#countPrice').text(res);
            }
          ) 
      }
    })
  })
</script>