
/**
* ajax 提交表单 到后台去验证然后回到前台提示错误
* 验证通过后,再通过 form 自动提交
*/
before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
function ajax_submit_form(form_id,submit_url){

         if(before_request == 0)
            return false;

            var th=$(this);
            console.log(th);
            //验证阶段
            th.find('input,select,button,textarea').each(function(){
              if (!verify($(this))) {
                return false;
              }
            });

	          $("[id^='err_']").hide();  // 隐藏提示
            $.ajax({
                        type : "POST",
                        url  : submit_url,
                        data : $('#'+form_id).serialize(),// 你的formid                
                        error: function(request) {
                                alert("服务器繁忙, 请联系管理员!");
                        },
                        success: function(v) {
                            console.log(v);
                            before_request = 1; // 标识ajax 请求已经返回
                                // 验证成功提交表单
                            if(v.status == 1)
                            {      					
                  						layer.msg(v.msg, {
                  						  icon: 1,   // 成功图标
                  						  time: 5000 //2秒关闭（如果不配置，默认是3秒）
                  						});													
              								if(v.url){
              									location.href = v.url;
              								}else{
              									location.href = location.href;
              								}

              							return true;
                                
                            }else{
                              layer.msg(v.msg, {
                                icon: 2,   // shibai图标
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                              });                      
                              return false;

                            }

                        }
            });   
            before_request = 0; // 标识ajax 请求已经发出
}

/**
* 在ajax 返回提示错误时， 输入框改变时提示 将隐藏
*/
$(document).ready(function(){
	/*
   $("input").click(function(){	           
	    var input_name = $(this).attr("name");   
		//$("#err_"+input_name).hide();
   });
   $("textarea").click(function(){    
	    var input_name = $(this).attr("name");   
		//$("#err_"+input_name).hide();
   });
   */   
	
});


/**
 *  Ajax通用提交表单
 *  @var  form表单的id属性值  form_id
 *  @var  提交地址 subbmit_url
 */

function post_form(form_id,subbmit_url){
    if(form_id == ''){
        alert('缺少必要参数');
        return false;
    }
    if(!subbmit_url){
        //  默认取当前地址  加上ajax请求标示
        subbmit_url = location.href + '/is_ajax/1';
    }
    //  序列化表单值
    var data = $('#'+form_id).serialize();

    $.post(subbmit_url,data,function(result){
        var obj = $.parseJSON(result);
        if(obj.status == 0){
            //alert(obj.msg);
            return false;
        }
        if(obj.status == 1){
            //alert(obj.msg);
            if(obj.data.return_url){
                //  返回跳转链接
                location.href = obj.data.return_url;
            }else{
                //  刷新页面
                location.reload();
            }
            return;
        }
    })
}


/**
 *  伪静态HTML处理
 *  @var  网址  url
 */
function convert_url(url){
   if(url){
       url = url.replace('.html','');
   }
    return url;
}
 
		// 输入框失去焦点 ajax 保存
//		$('input[name^="field_"]').on('blur', function () {
	function ajaxUpdateField(obj){
			 var table = $(obj).data('table');
			 var id = $(obj).data('id');			 
			 var field = $(obj).attr('name').replace(/field_/ig,""); // 字段名字
			 var value = $(obj).val();				 
			 $.ajax({
				 type:'POST',
				 data:{table:table,id:id, field:field,value:value}, 
				 url:"/index.php/admin/Goods/updateField",
				 success:function(data){					 
						  layer.msg('修改成功', {icon: 1,time:1000});
				 }        
			 });			 
	}
	//	});	