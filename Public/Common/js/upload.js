/**
 * Created by twenj on 2016/7/13.
 */
<!-- 上传图片 -->
//上传
$(function() {
    var idName = '';
    $('.upload_pic').uploadify({
        'formData':{'w':'','h':100},
        'buttonText':"上传图片",
        'uploader' 	: "/Biz/Common/uploadi",
        'onUploadSuccess':function(file,data,response){
            console.log(data);
            if(response == true){
                showimg(idName, data);
            }else{
                alert("上传失败");
            }
        },
        'onUploadStart':function(file){
            idName = this.settings.button_placeholder_id;
            //判断是否超过限定数量
            anum = $("."+idName+"list .product_banner").size();
            maxnum = $("."+idName+"list").attr("data-maxnum");
            if(maxnum != ''){
                if(anum >= Number(maxnum)){
                    alert("最多添加"+maxnum+"张图片");
                    this.cancelUpload(file.id);
                    $('#' + file.id).remove();
                }
            }
        }
    });
});
//显示上传完成的图片
function showimg(idName,data){
    var pic = $.parseJSON(data);
    if(pic.ret == 1){
        var listbox = $("."+idName+"list");
        if(typeof(listbox.attr("data-maxnum")) != "undefined"){
            listbox.append('<div class="product_banner"><img src="'+pic.thumb+'" height="100"><input type="hidden" name="' + idName + '[]" value="'+pic.pid+'"><div style="z-index:90; width:100%;"><div class="waterbanner1 bannerq">前移</div><div class="waterbanner1 bannerh" style="margin-left:5%; margin-right:5%;">后移</div><div class="waterbanner1 bannerclose">删除</div></div></div>');
        }else{
            listbox.html('<img src="'+pic.thumb+'" height="100"><input type="hidden" name="'+idName+'" value="'+pic.pid+'">');
        }
    }else{
        alert(data.msg);
    }
    return false;
}
$("body").on("click",".bannerclose",function(){
    if(!confirm("是否确认删除？")){
        return false;
    }
    $(this).parent().parent().remove();
})
$("body").on("click",".bannerq",function(){
    var zhe,qian;
    zhe=$(this).parent().parent().html();
    qian=$(this).parent().parent().prev().html();
    $(this).parent().parent().prev().html(zhe);
    $(this).parent().parent().html(qian);
})
$("body").on("click",".bannerh",function(){
    var zhe,hou;
    zhe=$(this).parent().parent().html();
    hou=$(this).parent().parent().next().html();
    $(this).parent().parent().next().html(zhe);
    $(this).parent().parent().html(hou);
})
