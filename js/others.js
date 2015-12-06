$(function(){

  $("#btn-feedback").click(
    function(e){
      e.preventDefault();
      $("#btn-feedback").attr("disabled","disabled");
      $("#btn-feedback").html("提交中。。。");
      var title = $("#f-title").val();
      var content = $("#f-content").val();
      var name = $("#f-name").val();
      var contact = $("#f-contact").val();
      var msg = "";
      if(!$.trim(title) || title.length>25){
	msg += "title";
      }
      if(!$.trim(content) || title.length>500){
	msg += "content";
      }
      if(msg.length != 0){
        alert(msg);
        $("#btn-feedback").removeAttr("disabled");
        $("#btn-feedback").html("提交反馈");
	return false;
      }
      
      var ajax = $.ajax({
        url: "service-feedback.php?action=feedback",
        type: 'POST',
        data: {
          title: title,
          content: content,
	  name: name,
	  contact: contact
        }
      });

      ajax.done(function(msg){
        if(msg=="true"){
          alert("ok");
        }else{
          alert(msg);
        }
        $("#btn-feedback").removeAttr("disabled");
        $("#btn-feedback").html("提交 ");
      });

      ajax.fail(function(jqXHR,textStatus){
        alert("Request failed :" + textStatus);
        $("#btn-feedback").html("提交反馈");
        $("#btn-feedback").removeAttr("disabled");
      });
    }
  );
  
});

function readMsg(callback,mid){
  $.get(
    "service-msg.php?action=read", 
    {
      id :mid
    }, 
    callback
  ); //end get
} //end function readMsg

function read(mid,url){
  readMsg(function(str) {
    if (str == "true"){
      return true;
    }else {
      return true;
    }
  },mid);
}
