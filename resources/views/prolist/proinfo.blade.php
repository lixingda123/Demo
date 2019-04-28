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
    <script type="text/javascript" src="/layui/layui.js"></script>
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
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      @for($i=1;$i<=count($showimg);$i++)
      <img src="/images/{{$showimg[$i]}}" />
      @endfor
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$data['goods_price']}}</strong></th>
       <td>
        
        <button id="add">＋</button>
        <input type="text" id="val" style="width:30px;"  value="1"/>
        <button id="less">－</button>
        <input type="hidden" id="num" name="" value="{{$data['goods_inventory']}}">
        
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$data['goods_name']}}</strong>
        <p class="hui">富含纤维素，平衡每日膳食</p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>
     <input type="hidden" id="_token" value="{{csrf_token()}}">
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="/images/image4.jpg" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="/index/index"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><button style="cursor: pointer;background:orange;border:none;" goods_id="{{$data['goods_id']}}" id="cart">加入购物车</button></td>
      </tr>
     </table>
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
    <!--焦点轮换-->
    <script src="/js/jquery.excoloSlider.js"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
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
      //点击加号
      $('#add').click(function(){
        var val=parseInt($('#val').val());
        var num=$('#num').val();
        val+=1;
        if(val>=num){
          $('#val').val(num);
          $('#add').attr('disabled','true');
          $('#less').removeAttr('disabled');
        }else{
          $('#val').val(val);
          $('#less').removeAttr('disabled');
        }
      })
      //点击减号
      $('#less').click(function(){
        var val=parseInt($('#val').val());
        val-=1;
        if(val<=1){
          $('#val').val(1);
          $('#add').removeAttr('disabled');
          $('#less').attr('disabled','true');
        }else{
          $('#add').removeAttr('disabled');
          $('#val').val(val);
        }
      })
      //输入框失去焦点
      $('#val').blur(function(){
        var val=parseInt($('#val').val());
        var num=$('#num').val();
        var reg=/^\d{1,}$/;
        if(!reg.test(val)){
          $('#val').val(1);
        }else if(val<=1){
          $('#val').val(1)
        }else if(val>=num){
          $('#val').val(num);
        }
      })
      //点击加入购物车
      $('#cart').click(function(){
        var val=parseInt($('#val').val());
        var goods_id=$('#cart').attr('goods_id');
        var _token=$('#_token').val();
        $.post(
          '/index/AddCar',
          {_token:_token,buy_num:val,goods_id:goods_id},
          function(res){
            if(res.code==3){
              layer.msg(res.font,{icon:res.code,time:1500},function(){
                location.href="/index/login";
              });
            }else{
              layer.msg(res.font,{icon:res.code});
              location.href="/index/car";
            }
            
          },
          'json'
        )
      })
    })
  })
</script>