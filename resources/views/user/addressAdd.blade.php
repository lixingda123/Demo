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
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/images/head.jpg" />
     </div><!--head-top/-->
     <form onsubmit="return false" class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" id="name" placeholder="收货人" /></div>
       <div class="lrList"><input type="text" id="address" placeholder="详细地址" /></div>
       <div class="lrList">
        <select class="sel" id="province">
          <option value="">省份/直辖市</option>
          @foreach($address as $k=>$v)
            <option value="{{$v->id}}">{{$v->name}}</option>
          @endforeach
        </select>
        <select class="sel" id="city">
         <option value="">区县</option>
        </select>
        <select class="sel" id="area">
         <option value="">详细地址</option>
        </select>
       </div>
       <div class="lrList"><input type="text" placeholder="手机" id="tel" /></div>
       <div class="lrList2"><input type="text" placeholder="设为默认地址" default="2" /> <button id="def">设为默认</button></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="保存" id="sub" />
      </div>
     </form><!--reg-login/-->
     <input type="hidden" id="_token" value="{{csrf_token()}}">
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="/index/index">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="/index/prolist">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="/index/car">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl class="ftnavCur">
       <a href="/index/user">
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
        var layer=layui.layer;
        _token=$('#_token').val();
        $('#sub').click(function(){
          var obj={};
          obj.name=$('#name').val();
          obj._token=_token;
          obj.address=$('#address').val();
          obj.province=$('#province').val();
          obj.city=$('#city').val();
          obj.area=$('#area').val();
          obj.tel=$('#tel').val();
          obj.is_default=$('#def').prev().attr('default');
          if(obj.name==''){
            layer.msg('收货人必填',{icon:2});
            return false;
          }else if(obj.address==''){
            layer.msg('详细地址必填',{icon:2});
            return false;
          }else if(obj.province==''){
            layer.msg('请选择省市',{icon:2});
            return false;
          }else if(obj.city==''){
            layer.msg('请选择城市',{icon:2});
            return false;
          }else if(obj.area==''){
            layer.msg('请选择区县',{icon:2});
            return false;
          }else if(obj.tel==''){
            layer.msg('手机号必填',{icon:2});
            return false;
          }
          $.post(
            "/index/addressAddDo",
            obj,
            function(res){
              if(res.code==1){
                layer.msg(res.font,{icon:res.code,time:1500},function(){
                  location.href="/index/user";
                });
              }else{
                layer.msg(res.font,{icon:res.code});
              }
            },
            'json'
          );
        })
        //三级联动
        $(document).on('change','.sel',function(){
          var _this=$(this);
            var a_id=_this.children();
            var id;
            var _option="<option value=''>--请选择--</option>";
            _this.nextAll().html(_option);
            a_id.each(function(index){
              if($(this).prop('selected')==true){
               id=$(this).val();
              }
            })
            $.post(
              "/index/getarea",
              {_token:_token,id:id},
              function(res){
                for(i in res['area']){
                  // console.log(res['area'][i]['id']);
                  _option+='<option value="'+res['area'][i]['id']+'">'+res['area'][i]['name']+'</option>';
                }
                _this.next().html(_option);
              },
              'json'
            )
        })
        //点击设为默认
        $('#def').click(function(){
          var val=$(this).text();
          if(val=='设为默认'){
            $(this).prev().attr('default','1');
            $(this).text('取消默认');
          }else{
            $(this).prev().attr('default','2');
            $(this).text('设为默认');
          }
        })
      })
    })
</script>