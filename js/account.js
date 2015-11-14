$(function(){
  $("#forget-pwd").click(
    function(){
      window.location.href = "lostpass.php";
    }
  );

  $("#btn-login").click(
    function(e){
      e.preventDefault();
      if($("#account").val()=="" || $("#password").val()==""){
        return false;
      }
      var account = $("#account").val();
      var password = $("#password").val();
      password = cryptPwd(password);
      //var pass = cryptPwd(password);
      var rememberme = false;
      if($("#rememberme").prop("checked")==true){
        rememberme = true;
      }
      $("#btn-login").html("登录中");
      $("#btn-login").attr("disabled","disabled");
      var ajax = $.ajax({
        url: "service-account.php?action=login",
        type: 'POST',
        data: {
          username: account,
          pwd: password,
          rememberme: rememberme
        }
      });

      ajax.done(function(msg){
        if(msg=="1"){
          window.location.reload(true);
        }else{
          alert(msg);
          $("#password").val("");
          $("#btn-login").html("登录");
          $("#btn-login").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-login").html("登录");
        $("#btn-login").removeAttr("disabled");
      });
    }
  );

  $("#btn-lostpass").click(
    function(e){
      //e.preventDefault();
      $("#btn-lostpass").html("submiting");
      $("#btn-lostpass").attr("disabled","disabled");
      if(!chkUsername($("#username").val()) || !chkEmail($("#email").val())){
        $("#lostpass-error-msg").html("用户不存在！");
        $("#lostpass-error").css("display","block");
        $("#lostpass").effect("shake");
        $("#btn-lostpass").html("找回密码");
        $("#btn-lostpass").removeAttr("disabled");
	      return false;
	    }
	    var username = $("#username").val();
	    var email = $("#email").val();
	    var ajax = $.ajax({
	      url: "service-account.php?action=lostPwd",
	      type: 'POST',
	      data: {
	        username: username,
	        email: email
	      }
	    });

      ajax.done(function(msg){
        if(msg=="0"){
          alert("重置密码邮件已发送到您的邮箱");
          window.location.href = "index.php";
        }else{
          $("#lostpass-error-msg").html("无法发送邮件("+ msg +"),<br/> <a href='help.php#qid-9'>查看原因</a>");
          $("#lostpass-error").css("display","block");
          $("#lostpass").effect("shake");
          $("#btn-lostpass").html("找回密码");
          $("#btn-lostpass").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-lostpass").html("找回密码");
        $("#btn-lostpass").removeAttr("disabled");
      });
    }
  );

  $("#btn-resetpwd").click(
    function(e){
      e.preventDefault();
      $("#btn-resetpwd").html("请求中。。。");
      $("#btn-resetpwd").attr("disabled","disabled");
      if(!chkUsername($("#username").val())){
        $("#resetpwd-error-msg").html("用户不存在！");
        $("#resetpwd-error").css("display","block");
        $("#resetpwd").effect("shake");
        $("#btn-resetpwd").html("重置密码");
        $("#btn-resetpwd").removeAttr("disabled");
	      return false;
	    }
	    var username = $("#username").val();
	    var authKey = $("#auth-key").val();
	    var newPwd = $("#newpwd").val();
      newPwd = cryptPwd(newPwd);
	    var ajax = $.ajax({
	      url: "service-account.php?action=reset",
	      type: 'POST',
	      data: {
	        username: username,
					pwd: newPwd,
	        auth_key: authKey
	      }
	    });

      ajax.done(function(msg){
        if(msg=="1"){
          alert("密码已成功重置！");
          window.location.href = "index.php";
        }else{
          $("#resetpwd-error-msg").html(msg);
          $("#resetpwd-error").css("display","block");
          $("#resetpwd").effect("shake");
          $("#btn-resetpwd").html("重置密码");
          $("#btn-resetpwd").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-resetpwd").html("重置密码");
        $("#btn-resetpwd").removeAttr("disabled");
      });
    }
  );

  $("#btn-register").click(
    function(e){
      e.preventDefault();
      $("#btn-register").html("注册中。。。");
      $("#btn-register").attr("disabled","disabled");

      var username = $("#r-username").val();
      var email = $("#r-email").val();
      var password = $("#r-password").val();
      password = cryptPwd(password);

      var msg = "";
      if(!chkUsername( username )){
        msg += "*用户名 1-15 位，且不能包含特殊字符" + "<br/>";
      }
      if(!chkEmail( email )){
        msg += "*不识别的邮箱" + "<br/>";
      }

      if(msg.length != 0){
	      alert(msg);
        $("#btn-register").html("注册");
        $("#btn-register").removeAttr("disabled");
        return false;
      }

      var ajax = $.ajax({
        url: "service-account.php?action=reg",
        type: 'POST',
        data: {
          username: username,
          email: email,
          pwd: password
        }
      });

      ajax.done(function(msg){
        if(msg=="1"){
          alert("注册成功，欢迎加入吉大易物！");
          window.location.reload(true);
        }else{
          alert(msg);
          $("#btn-register").html("注册");
          $("#btn-register").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-register").html("注册");
        $("#btn-register").removeAttr("disabled");
      });
    }
  );

/*
  $("#r-gender").focus(function(){
    var index = Math.floor(Math.random()*8 + 1);
    $("#r-response-img").attr("src","img/r" + index + ".jpg");
  });
*/

  $("#btn-change-info").click(
    function(e){
      //e.preventDefault();
      $("#btn-change-info").html("修改中。。。");
      $("#btn-change-info").attr("disabled","disabled");

      var email = $("#u-email").val();
      var gender = $("#u-gender").val();
      var studentNo = $("#u-studentNo").val();
      var campus = $("#u-campus").val();
      var qq = $("#u-qq").val();
      var phone = $("#u-phone").val();
      var hidePhone = $("#u-hidePhone").prop("checked");

      var msg = "";
      if(!chkEmail( email )){
        msg += "*不识别的邮箱" + "<br/>";
      }
      if(!chkQQ( qq )){
        msg += "*QQ帐号不正确（仅支持数字帐号）" + "<br/>";
      }
      if(!chkPhoneNumber(phone)){
        msg += "*手机号不支持，请输入 11 位手机号码" + "<br/>";
      }

      if(msg.length != 0){
        alert(msg);
        $("#btn-change-info").html("确认修改");
        $("#btn-change-info").removeAttr("disabled");
        return false;
      }

      var ajax = $.ajax({
        url: "service-account.php?action=updateInfo",
        type: 'POST',
        data: {
          email: email,
          gender: gender,
          studentNo: studentNo,
          campus: campus,
          qq: qq,
          phone: phone,
          hidePhone: hidePhone
        }
      });

      ajax.done(function(msg){
        if(msg=="1"){
          alert("更新个人信息成功！");
          $("#btn-change-info").html("确认修改");
          $("#btn-change-info").removeAttr("disabled");
        }else{
          alert(msg);
          $("#btn-change-info").html("确认修改");
          $("#btn-change-info").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-change-info").html("修改信息");
        $("#btn-change-info").removeAttr("disabled");
      });
    }
  );

  $("#btn-changepwd").click(
    function(e){
      e.preventDefault();
      if($("#oldpwd").val()=="" || $("#newpwd").val()==""){
        return false;
      }
      var oldpwd = $("#oldpwd").val();
      var newpwd = $("#newpwd").val();
      if(newpwd.length < 6){
        alert('密码长度不少于6位');
        return false;
      }
      oldpwd = cryptPwd(oldpwd);
      newpwd = cryptPwd(newpwd);
      //var pass = cryptPwd(password);
      $("#btn-changepwd").html("修改中");
      $("#btn-changepwd").attr("disabled","disabled");
      var ajax = $.ajax({
        url: "service-account.php?action=changePwd",
        type: 'POST',
        data: {
          oldpwd: oldpwd,
          newpwd: newpwd
        }
      });

      ajax.done(function(msg){
        if(msg=="1"){
          alert("密码已成功修改");
          $("#btn-changepwd").html("修改密码");
          $("#btn-changepwd").removeAttr("disabled");
        }else{
          alert(msg);
          $("#newpwd").val("");
          $("#btn-changepwd").html("修改密码");
          $("#btn-changepwd").removeAttr("disabled");
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-changepwd").html("修改密码");
        $("#btn-changepwd").removeAttr("disabled");
      });
    }
  );



});



  function chkUsername(username){
    if(username.length==0 || username.length>15 || username.indexOf("@")!=-1){
	    return false;
    }else{
      return true;
    }
  }


  function chkEmail(email){
    var emailRegex = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    if(!$.trim(email)){
      return false;
    }else if(!(emailRegex.test(email))){
      return false;
    }else {
      return true;
    }
  }

  function chkPhoneNumber(phoneNumber){
    var phoneNumberRegex = /^1[0-9]{10}$/;
    /* allow null*/
    if(phoneNumber.length==0)
	    return true;
    if(!(phoneNumberRegex.test(phoneNumber))){
      return false;
    }else {
      return true;
    }
  }

  function chkQQ(qq){
    //allow null
    if(qq.length==0)return true;
    return !isNaN(qq) && qq>10000;
  }

  function cryptPwd(password){
    password = window.md5(password);
    password = window.md5(password + "jluewu");
    password = window.md5(password + "com");
    return password;
  }
