//验证输入框
function verify(th){
    if (th.hasClass('verify')) {
        if (th.val()=='' || th.val()==null || th.val()==undefined) {
            // if(th.hasClass('no')){
            //     th.addClass('');
            // }else{
                th.addClass('error');
            // }
            th.nextAll('.errMsg').text(th.attr('errMsg')?th.attr('errMsg'):'请输入内容');
        }else{
            th.removeClass('error');
            th.nextAll('.errMsg').text('');
        }
    }
    if (th.val()!='' && th.val()!=null && th.val()!=undefined) {
        var matarr = new Array();
        matarr[0]= new Array('phoneNumber',/^(0|86|17951)?((1[3|4|5|7|8][0-9]{1})+\d{8})$/,'请输入正确手机号'); //手机正则
        matarr[1]= new Array('telNumber',/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,9}(-\d{1,8})?$/,'请输入正确手机号或座机号');   //手机座机正则
        matarr[2]= new Array('passWord',/^(\w){6,20}$/,'请输入6-20个字母、数字、下划线');
        matarr[3]= new Array('userName',/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/,'请输入5-20个以字母开头、可带数字、“_”、“.”的内容');
        matarr[4]= new Array('timeYmd',/^(\d{4})-(0\d{1}|1[0-2])-(0\d{1}|[12]\d{1}|3[01])$/,'请输入（如：1970-01-01）格式的内容');
        matarr[5]= new Array('timeHis',/^(0\d{1}|1\d{1}|2[0-3]):[0-5]\d{1}:([0-5]\d{1})$/,'请输入（如：00:00:00）格式的内容');
        matarr[6]= new Array('idCard',/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/,'身份证号不正确'); 
        matarr[7]= new Array('bankCard',/^(\d{16}|\d{19})$/,'银行卡号不正确'); 
        
        for (var i=0; i<matarr.length; i++) {
            if (th.hasClass(matarr[i][0])) {
                if (!th.val().match(matarr[i][1])) {
                    th.addClass('error');
                    th.nextAll('.errMsg').text(th.attr('errMsg')?th.attr('errMsg'):'请输入内容');
                    return false;
                }else{
                    th.removeClass('error');
                    th.nextAll('.errMsg').text('');
                    return true;
                }
            }
        }
    }
}
$(document).ready(function () {
        $('#leftnav_change').click(function(){
            if ($('#leftnav_change>div').hasClass('glyphicon-chevron-right')) {
                $('#leftnav_change>div').attr('class','glyphicon glyphicon-chevron-left');
                $('nav.navbar-default.navbar-side').hide(500);
                $('#page-wrapper').css('margin-left',0);
            }else{
                $('#leftnav_change>div').attr('class','glyphicon glyphicon-chevron-right');
                $('nav.navbar-default.navbar-side').show(500);
                $('#page-wrapper').css('margin-left','260px');
            } 
        });
        $('form[way="ajax"]').submit(function(event){
            event.preventDefault();
            var ys = $(this);
            ys.find('input,select,button,textarea').each(function(){
                verify($(this));
            })
            if (ys.find('.error').length>0) {
                return false;
            }
            var fd = new FormData(ys[0]);
            $.ajax({
                url:ys.attr("action"),
                type:ys.attr("method"),
                data:fd,
                processData: false,  // 告诉jQuery不要去处理发送的数据
                contentType: false,  // 告诉jQuery不要去设置Content-Type请求头
                success:function(data){
                    if (data) {                    
                        if (data['status']==1) {
                            if (data['type']=="toubiao") {
                                        win('win-tbsuccess');
                                        setTimeout(function(){location.href = data.url}, 3000);
                                        
                            }else{
                                layer.msg(data.msg, {
                                  icon: 1,
                                  time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                }, function(){
                                  if(data.url){
                                            location.href = data.url;
                                    }else{
                                        location.href = location.href;
                                    }
                                });
                            }
                        }else{
                            layer.msg(data.msg, {
                                          icon: 2,   // 成功图标
                                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                        });
                        	return false;
                        }

                    }else{
                        if (data['url']) {
                            location.href = data['url'];
                        }
                    }
                }
            });
        });
    });

$(function(){
    $('#z').blur(function(){
        // var url=$('.tiaozhuan').attr("href");
        // var strs=url.substr(0,25);

        var url = window.location.href;
        // alert(url);
        var value=this.value;
        var all=$('.all').attr("title");
        var i=parseFloat(all);//将字符转化成数字

        if(value<=i && !isNaN(value) && value>0){
        value=Math.ceil(value);
        }else{
        value='';
        }

        var newurl=url+'/p/'+value;
        $('.tiaozhuan').attr("href",newurl);
    });
});