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
    <style type="text/css">
      .bgcolor{
        background-color: orange;
      }
    </style>
  </head>
  <body>

    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form class="prosearch"><input type="text" id="search" /></form>
      </div>
     </header>
     <ul class="pro-select">
      <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
      <li class="new" status="1"><a href="javascript:;">新品</a></li>
      <li class="new" status="2"><a href="javascript:;">精品</a></li>
      <li class="new" status="3"><a href="javascript:;">价格 <span id="sp">↓</span></a></li>
     </ul><!--pro-select/-->
    <div id="replace">
     <div class="prolist">
      @foreach($data as $k=>$v)
      <dl>
       <dt><a href="/prolist/proinfo/{{$v->goods_id}}"><img src="/images/{{$v->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/prolist/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥{{$v->goods_bzprice}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach
     </div><!--prolist/-->
    </div>
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="index">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl class="ftnavCur">
       <a href="prolist">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="car">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl>
       <a href="user">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
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
  </body>
</html>
<script type="text/javascript">
  $(function(){
    layui.use('layer',function(){
      // 点击新品精品价格
      $('.new').click(function(){
        var _this=$(this);
        var status=_this.attr('status');
        var _token=$('#token').val();
        var val=$('#search').val();
        var order;
        _this.addClass('bgcolor');
        _this.siblings().removeClass('bgcolor');
        if(status==3){
          if($('#sp').text()=='↑'){
            order="asc";
            $('#sp').text('↓');
          }else{
            order="desc";
            $('#sp').text('↑');
            
          }
          
        }
        $.post(
          "getNewGoods",
          {_token:_token,status:status,val:val,order:order},
          function(res){
            $('#replace').html(res);
          }
        )
      })
      //搜索
      $('#search').change(function(){
        var status=$('li[class="new bgcolor"]').attr('status');
        // console.log(status);
        var _token=$('#token').val();
        var val=$('#search').val();
        var order;
        if(status==3){
          if($('#sp').text()=='↑'){
            order="desc";
          }else if($('#sp').text()=='↓'){
            order='asc';
          } 
        }
        // console.log(order);
        $.post(
          "getNewGoods",
          {_token:_token,status:status,val:val,order:order},
          function(res){
            $('#replace').html(res);
          }
        )
      })
    })
  })
</script>