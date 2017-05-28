/**
 * 通用的js判断
 */
/**
 * 判断邮箱格式是否正确
 */
function check_email(data, exists){
    if(exists == '' || typeof(exists) == 'undefined'){
        exists = 2;
    }

    var res = new Array();
    var result;
    result = isNotNull(data);
    if(result == false){
        res.res = 0;
        res.msg = "邮箱不能为空";
        return res;
    }
    if(strlen(data) > 64){
        res.res = 0;
        res.msg = "邮箱长度不能超过64个字符";
        return res;
    }
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(filter.test(data) != true){
        res.res = 0;
        res.msg = "邮箱格式错误";
        return res;
    }
    if(exists == 2){
        rarr = '';
        $.ajax({
            url:'/Home/Ajax/checkEmailIsExists',
            type:'post',
            data:{'data':data},
            dataType:'json',
            async:false,
            success:function(rearr){
                rarr = rearr;
            }
        })
        if(rarr.res != 1){
            res.res = 0;
            res.msg = rarr.msg;
            return res;
        }
    }else if(exists == 3){
        rarr = '';
        $.ajax({
            url:'/Home/Ajax/checkEmailIsReg',
            type:'post',
            data:{'data':data},
            dataType:'json',
            async:false,
            success:function(rearr){
                rarr = rearr;
            }
        })
        if(rarr.res != 1){
            res.res = 0;
            res.msg = rarr.msg;
            return res;
        }
    }

    res.res = 1;
    return res;
}

/**
 * 判断手机格式是否正确
 */
function check_phone(data){
    var res = new Array();
    var result;
    result = isNotNull(data)
    if(result == false){
        res.res = 0;
        res.msg = "手机号码不能为空";
        return res;
    }
    if(strlen(data) > 20){
        res.res = 0;
        res.msg = "手机号码不能超过20个字符";
        return res;
    }
    var filter = /^(0|86|17951)?(13[0-9]|15[012356789]|17[0678]|18[0-9]|14[57])[0-9]{8}$/;
    if(filter.test(data) != true){
        res.res = 0;
        res.msg = "手机号码格式错误";
        return res;
    }
    res.res = 1;
    return res;
}

/**
 * 判断密码是否正确
 */
function check_password(data){
    var res = new Array();
    var filter = /^[0-9a-zA-Z_]{6,20}$/;
    if(filter.test(data) != true){
        res.res = 0;
        res.msg = "密码为6-20位字符,包括数字，字母和_";
        return res;
    }
    res.res = 1;
    return res;
}

/**
 * 判断重复密码是否正确
 */
function check_repasswd(data, password){
    var res = new Array();
    result = isNotNull(data);
    if(result == false){
        res.res = 0;
        res.msg = "确认密码不能为空";
        return res;
    }
    if(data != password){
        res.res = 0;
        res.msg = "确认密码与密码不一致";
        return res;
    }
    res.res = 1;
    return res;
}
/**
 * 判断重复密码是否正确
 */
function check_oldpasswd(data){
    var res = new Array();
    result = isNotNull(data);
    if(result == false){
        res.res = 0;
        res.msg = "原始密码不能为空";
        return res;
    }
    $.ajax({
        url:'/Home/Ajax/checkOldpasswd',
        type:'post',
        data:{'data':sha1(data)},
        dataType:'json',
        async:false,
        success:function(rearr){
            rarr = rearr;
        }
    })
    if(rarr.res != 1){
        res.res = 0;
        res.msg = rarr.msg;
        return res;
    }
    res.res = 1;
    return res;
}

/**
 * 判断参数是否为空若为空则返回false
 */
function isNotNull(data){
    if(data == '' || typeof(data) == 'undefined'){
        return false;
    }
    return true;
}
/**
 * 判断是否为正整数
 */
function isInt(num, zero){
    num = Number(num);
    if(zero == 1){
        var filter = /^[0-9]*$/;
    }else{
        var filter = /^[0-9]*[1-9]+[0-9]*$/;
    }
    res = filter.test(num);
    return res;
}
/**
 * 获取字符长度（包含中文）
 */
function strlen(str){
    var c;
    var len = 0;
    for(var i = 0; i < str.length; i++){
        c = str.charCodeAt(i);
        if(c > 127 || c == 4){
            len += 3;
        }else{
            len++;
        }
    }
    return len;
}
/**
 * 判断字符数是否正确
 */
function check_strlen(str, min, max){
    var res = new Array();
    var len = strlen(str);
    if(len < min){
        res.res = 0;
        if(min <= 1){
            res.msg = "不能为空";
        }else{
            res.msg = "不能小于" + min + "个字符";
        }
        return res;
    }
    if(len > max){
        res.res = 0;
        res.msg = "不能大于" + max + "个字符";
        return res;
    }

    res.res = 1;
    return res;
}
/**
 * 判断价钱格式是否正确
 */
function checkFloatFormat(data){
    var re = /^(\d*\.)?\d+$/;
    if(re.test(data) == false){
        return false;
    }

    return true;
}








