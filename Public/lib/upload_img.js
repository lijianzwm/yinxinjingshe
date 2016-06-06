$(document).ready(function(){
    //选择文件成功则提交表单
    $("#upload_file").change(function(){
        if($("#upload_file").val() != '') $("#submit_form").submit();
    });
    //iframe加载响应，初始页面时也有一次，此时data为null。
    $("#exec_target").load(function(){
        var data = $(window.frames['exec_target'].document.body).find("textarea").html();
        //若iframe携带返回数据，则显示在feedback中
        if(data != null){
            $("#preview").append(data.replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
            $("#upload_file").val('');
            $('#cropbox').Jcrop({
                aspectRatio: "{:C('JCROP_ASPECT_RATIO')}",
                onSelect: updateCoords
            });
        }
    });
});

$(function(){
    $('#cropbox').Jcrop({
        aspectRatio: 1,
        onSelect: updateCoords
    });

});

function jcrop(url){
    xx = document.getElementById("x").value;
    yy = document.getElementById("y").value;
    ww = document.getElementById("w").value;
    hh = document.getElementById("h").value;
    var aj = $.ajax( {
        url:url,// 跳转到 action
        data:{
            imgName : '{$imgName}',
            tmpImgName : '{$tmpImgName}',
            x : xx,
            y : yy,
            w : ww,
            h : hh
        },
        type:'post',
        cache:false,
        dataType:'json',
        success:function(data) {
            if(data.error_code == 0 ){

            }else{
                alert(data.msg);
            }
        },
        error : function() {
            alert("异常！");
        }
    });
    hideJcrop();
    document.getElementById("imgView").innerHTML = "<img src='{:C('IMG_LOADING')}' />";
    setTimeout(function () {
        document.getElementById("imgView").innerHTML = "<img src='{$imgViewPath}?seed="+ parseInt(Math.random()*10000) +"' />";
    }, 3000);
}

function showJcrop(){
    document.getElementById("newsInfoDiv").style = "display:none";
    document.getElementById("jcropDiv").style = "display:block";
}

function hideJcrop(){
    document.getElementById("newsInfoDiv").style = "display:block";
    document.getElementById("jcropDiv").style = "display:none";
}