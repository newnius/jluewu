var shotCnt = 1;
$(function(){

  $("#btn-comment").click(
    function(e){
      e.preventDefault();
      $("#btn-comment").attr("disabled","disabled");
      $("#btn-comment").html("提交中。。。");
      var pid = $("#pid").val();
      var to = $("#to").val();
      var content = $("#content").val();
      if(isNaN(pid) || to.length < 1){
        $("#btn-comment").removeAttr("disabled");
        $("#btn-comment").html("提交留言");
        return false;
      }else if(content.length < 1 || content.length > 100){
        $("#btn-comment").removeAttr("disabled");
        $("#btn-comment").html("提交留言");
        return false;
      }
      var ajax = $.ajax({
        url: "service-comment.php?action=postComment",
        type: 'POST',
        data: {
          pid: pid,
          to: to,
          content: content
        }
      });

      ajax.done(function(msg){
        if(msg=="1"){
          $("#comment-msg").html('提交成功');
          var comment = '<div class="comment" id="cid-999"><a href="#">我</a>&nbsp;&nbsp;@<a href="goods_list.php?owner='+ to +'">'+ to +'</a>&nbsp;&nbsp;&nbsp;<p>' + content + '</p><br/></div><hr/>';
          $("#new-comment").before(comment);
        }else{
          $("#comment-msg").html(msg);
        }
        $("#btn-comment").removeAttr("disabled");
        $("#btn-comment").html("提交留言");
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-comment").html("提交留言");
        $("#btn-comment").removeAttr("disabled");
      });
    }
  );


  $("#btn-search").click(function(e){
    e.preventDefault();
    if($("#input-search").val().length < 1){
      return false;
    }else{
      window.location.href = "goods_list.php?name=" + $("#input-search").val();
    }
  });

  $("#add-shot").click(function(){
    $("#new-shot").click();
  });

  $("#new-shot").change(function(){
    loadImage(this);
  });


  $("#btn-publish").click(
    function(e){
      e.preventDefault();
      $("#btn-publish").attr("disabled","disabled");
      $("#btn-publish").html("提交中。。。");
      var goodsName = $("#goodsName").val();
      var goodsCategory = $("#goodsCategory").val();
      var goodsType = $("#goodsType").val();
      var goodsPrice = $("#goodsPrice").val();
      var goodsDepreciation = $("#goodsDepreciation").val();
      var goodsDescription = $("#goodsDescription").val();
      var goodsCampus = $("#goodsCampus").val();

      var formData = new FormData($("#form-publish")[0]);
      formData.append("name", goodsName);
      formData.append("category", goodsCategory);
      formData.append("type", goodsType);
      formData.append("price", goodsPrice);
      formData.append("campus", goodsCampus);
      formData.append("depreciation", goodsDepreciation);
      formData.append("description", goodsDescription);


      var ajax = $.ajax({
        url: "service-product.php?action=publish",
        type: 'POST',
        data: formData,
              processData:false,
              contentType: false
      });

      ajax.done(function(msg){
        if(msg=="1"){
		alert("发布宝贝成功");
               window.location.href="goods_list.php";
        }else{
        $("#btn-publish").html("发布");
        $("#btn-publish").removeAttr("disabled");
		alert(msg);
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-publish").html("发布");
        $("#btn-publish").removeAttr("disabled");
      });
    }
  );

  $("#btn-update").click(
    function(e){
      e.preventDefault();
      $("#btn-update").attr("disabled","disabled");
      $("#btn-update").html("提交中。。。");
      var goodsId = $("#goodsId").val();
      var goodsName = $("#goodsName").val();
      var goodsCategory = $("#goodsCategory").val();
      var goodsType = $("#goodsType").val();
      var goodsPrice = $("#goodsPrice").val();
      var goodsDepreciation = $("#goodsDepreciation").val();
      var goodsDescription = $("#goodsDescription").val();
      var goodsArea = $("#goodsArea").val();
      var tel = $("#tel").val();

      var formData = new FormData($("#form-publish")[0]);
      formData.append("id", goodsId);
      formData.append("pname", goodsName);
      formData.append("category", goodsCategory);
      formData.append("type", goodsType);
      formData.append("price", goodsPrice);
      formData.append("area", goodsArea);
      formData.append("depreciation", goodsDepreciation);
      formData.append("description", goodsDescription);
      formData.append("tel", tel);


      var ajax = $.ajax({
        url: "service-product.php?action=update",
        type: 'POST',
        data: formData,
              processData:false,
              contentType: false
      });

      ajax.done(function(msg){
        if(msg=="true"){
          alert("修改宝贝信息成功");
          window.location.href="ucenter.php?goods";
        }else{
          $("#btn-update").html("确认修改");
          $("#btn-update").removeAttr("disabled");
          alert(msg);
        }
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-update").html("发布");
        $("#btn-update").removeAttr("disabled");
      });
    }
  );

});

function comment_to(to){
  $("#comment-to").html("@" + to);
  $("#to").val(to);;
}

  function loadImage(context){
    var file = context.files[0];
    var reader = new FileReader()
    reader.onloadend = function(){
      $("#img-shot").attr("src",reader.result);
      $("#img-shot").unbind();
      $("#img-shot").removeAttr("id");
      $("#add-icon").unbind();
      $("#add-icon").remove();
      $("#add-block").unbind();
      $("#add-block").click(function(){removeImage(this)});
      $("#add-block").removeAttr("id");
      $("#add-shot").unbind();
      $("#add-shot").removeAttr("id");
      $("#new-shot").unbind();
      $("#new-shot").removeAttr("id");

      var newImgBlock = '<div id="add-block" class="img-block"><div id="add-shot"><span id="add-icon"  class="btn btn-default big-icon glyphicon glyphicon-plus img-responsive"></span><img id="img-shot" class="img-preview" /></div><input id="new-shot" name="shot-'+ shotCnt +'" class="hidden" type="file" /></div>';
      shotCnt++;
      $(context).parent().after(newImgBlock);
      $("#add-shot").click(function(){
        $("#new-shot").click();
      });
      $("#new-shot").change(function(){
        loadImage(this);
      });
    }
    if(file){
	        if(!/image\/\w+/.test(file.type)){
	            alert("文件必须为图片！");
	            return false;
	        }
      reader.readAsDataURL(file);
    }
  }

  function removeImage(context){
    $(context).remove();
  }
