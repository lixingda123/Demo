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
      <script src="http://cdn.bootcss.com/respond.//js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="maincont">
<header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/images/head.jpg" />
     </div><!--head-top/-->
     <form action="/index/regAdd" method="post" class="reg-login">
      <h3>已经有账号了？点此<a class="orange" href="login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" name="user_email" placeholder="输入手机号码或者邮箱号" id="tel"></div>
       <div class="lrList2"><input type="text" name="user_code" placeholder="输入短信验证码" /> <input type="button" id="aa" value="获取验证码"></div>
       <div class="lrList"><input type="password" name="user_pwd" placeholder="设置新密码（6-18位数字或字母）" id="pwd"></div>
       <div class="lrList"><input type="password" name="user_repwd" placeholder="再次输入密码" id="password"></div>
      </div>
      <div class="lrSub">
       <input type="submit" value="立即注册" id="bb">
      </div>
     </form>
      </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
  </body>
</html>
      <script src="{{asset('/js/jquery-3.2.1.min.js')}}"></script>
     <script type="text/javascript">
       $(function(){
       var falg=false;
        //获取验证码
        $('#aa').click(function(){
          var _this=$(this);
          var tel=_this.parent('div').prev('div').find('input').val();
          var reg=/^\d{11}$/;
          var regs=/^\w+@\w+\.com$/;
          if(reg.test(tel)){
              $.post(
                  '/index/tel',
                  {tel:tel},
                  function(res){
                    if(res.code=1){
                      _this.parent('div').after("<span style='color:red'>"+res.msg+"</sapn>");
                      $("input[name='type']").val(1);
                    }else{
                      _this.parent('div').after("<span style='color:red'>"+res.msg+"</sapn>");
                    }
                  }
                );
          }else if(tel==''){
              alert('邮箱和手机号必填');
              return false;
          }else if(regs.test(tel)){
                $.post(
                  '/index/email',
                  {tel:tel},
                  function(res){
                    if(res.code=1){
                      _this.parent('div').after("<span style='color:red'>"+res.msg+"</sapn>");
                      $("input[name='type']").val(2);
                    }else{
                      _this.parent('div').after("<span style='color:red'>"+res.msg+"</sapn>");
                    }
                  }
                );
          }
        })
        //验证密码
        $('#pwd').blur(function(){
          var pwd=$('#pwd').val();
          $('#pwd').next().remove();
          if(pwd==''){
            $('#pwd').after('<span style="color:red">密码不能为空</span>');
            flag=false;
            return false;
          }else{
            flag=true;
          }
          var reg=/^[a-z0-9]{6,18}$/;
          $('#pwd').next().remove();
            if(!reg.test(pwd)){
                $('#pwd').after('<span style="color:red">密码是6-18位数字或字母</span>');
                flag=false;
                return false;
            }else{
            flag=true;
          }
          })
        //验证确认密码
        $('#password').blur(function(){
          var password=$(this).val();
          var pwd=$('#pwd').val();
          $('#password').next().remove();
          if(password==''){
            $('#password').after('<span style="color:red">再次输入密码不能为空</span>');
            flag=false;
            return false;
          }else{
            flag=true;
          }
          $('#password').next().remove();
          if(password!=pwd){
            $('#password').after('<span style="color:red">再次输入密码必须跟密码保持一致</span>');
            flag=false;
            return false;
          }else{
            flag=true;
          }
          })
        //立即注册
        $('#bb').click(function(){
          $('#pwd').trigger('blur');
          $('#aa').trigger('blur');
          $('#password').trigger('blur');
          if(flag==true){
            return true;
          }else{
            alert('必填项不能为空');
            return false;
          } 
          })
        })
     </script>