<?php
/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */

// 微信公众号AppId和AppSecret
const APPID = '';
const APPSECRET = '';
// 云片网短信接口apikey
const APIKEY = '';

function returnJSON($ret,$msg='',$data=array()){
	if(gettype($ret)==='array'){
		$res = $ret;
	}else{
		$res['ret'] = $ret;
		$res['msg'] = $msg;
		$res['data'] = ($data==null)?array():$data;
	}
	if(!headers_sent()){
		header('Content-Type:application/json; charset=utf-8');
	}
	exit(json_encode($res));
}

function encrypt($str){
	return md5(C("AUTH_CODE").$str);
}

function vdump(){
	$var = func_get_args();
	$str = '';
	if(empty($var)){
		return $str;
	}
	foreach ($var as $v) {
		ob_start();
		var_dump($v);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
		}
		$str .= $output;
		unset($output);
	}
	echo '<pre>'.$str.'</pre>';
}

function toGBK($str){
	return iconv("UTF-8", "GBK//IGNORE", $str);
}

function toUTF8($str){
	return iconv("GBK", "UTF-8//IGNORE", $str);
}


// 格式化数字
function num_fmt($number,$decimals=3){
	return number_format($number, $decimals, '.', '');
}
// 数字转汉字
function num2ch($num,$mode=true) {
	$char = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
	$dw = array("","拾","佰","仟","","萬","億","兆");
	$dec = "點";
	$retval = "";
	if($mode)
		preg_match_all("/^0*(\d*)\.?(\d*)/",$num, $ar);
	else
		preg_match_all("/(\d*)\.?(\d*)/",$num, $ar);
	if($ar[2][0] != "")
		$retval = $dec . num2ch($ar[2][0],false); //如果有小数，则用递归处理小数
	if($ar[1][0] != "") {
		$str = strrev($ar[1][0]);
		for($i=0;$i<strlen($str);$i++) {
			$out[$i] = $char[$str[$i]];
			if($mode) {
				$out[$i] .= $str[$i] != "0"? $dw[$i%4] : "";
				if($str[$i]+$str[$i-1] == 0)
					$out[$i] = "";
				if($i%4 == 0)
					$out[$i] .= $dw[4+floor($i/4)];
			}
		}
		$retval = join("",array_reverse($out)) . $retval;
	}
	return $retval;
}

// 根据汉字获取拼音
function getSpell($str){
	$pinyin = \Org\Self\Pinyin::parse($str);
	$res['name'] = $pinyin['src'];
	$res['spelling'] = preg_replace('/[^a-z0-9]/','', $pinyin['pinyin']);
	$res['initial'] = preg_replace('/[^a-z0-9]/','', $pinyin['letter']);
	return $res;
}

// 验证条形码
function checkBarCode($code){
	if(preg_match("/^[0-9]{1,7}$/",$code)){			// goods_id
		$prefix = C('BARCODE_PREFIX');
		$middle = substr(10000000+$code, 1);
		$suffix = genBarCodeLast($prefix.$middle);
		$barCode = $prefix.$middle.$suffix;
	}else if(preg_match("/^[0-9]{12}$/",$code)){	// 12位条码
		$suffix = genBarCodeLast($code);
		$barCode = $code.$suffix;
	}else if(preg_match("/^[0-9]{13}$/",$code)){	// 13位条码
		$suffix = genBarCodeLast(substr($code, 0, 12));
		if($suffix==substr($code, 12)){
			$barCode = $code;
		}else{
			$barCode = false;
		}
	}else{
		$barCode = false;
	}
	return $barCode;
}
// 根据12位条码生成第13位校验码
function genBarCodeLast($barcode){
	$prepare = str_split($barcode);
	$oddSum  = 0;
	$evenSum = 0;
	foreach($prepare as $k=>$v){
		if($k%2==0){		//奇数位
			$oddSum  += $v;
		}else{				//偶数位
			$evenSum += $v;
		}
	}
	$Sumlast = ($oddSum + $evenSum*3) % 10;
	$suffix = ($Sumlast==0) ? 0 : (10-$Sumlast);
	return $suffix;
}

// 跳转到微信授权
function redirectOAuth($state='forestall'){
	$site_url = 'http://'.I('server.HTTP_HOST').I('server.REQUEST_URI');
	$OAuth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri='.urlencode($site_url).'&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
	redirect($OAuth_url);
}

// 网页授权获取用户openid
function getOpenID($code,$state='forestall'){
	if(I('get.state')!=$state){
		exit('Step1/2 用户信息获取失败');
	}

	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$code.'&grant_type=authorization_code';

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);

	// 显示获得的数据
	// $data = '{"access_token":"ACCESS_TOKEN","expires_in":7200,"refresh_token":"REFRESH_TOKEN","openid":"OPENID","scope":"SCOPE"}';
	// $data = '{"errcode":40029,"errmsg":"invalid code"}';
	$res = json_decode($data,true);
	if(isset($res['errcode']) || strlen($res['openid'])<28){
		exit('Step2/2 用户信息获取失败');
	}
	return $res['openid'];
}

/*
// 跳转到微信授权
function redirectOAuth($state='forestall'){
	$site_url = 'http://'.I('server.HTTP_HOST').I('server.REQUEST_URI');
	$OAuth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri='.urlencode($site_url).'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
	redirect($OAuth_url);
}

// 网页授权获取用户openid
function getOpenID($code,$state='forestall'){
	if(I('get.state')!=$state){
		exit('Step2.1 用户信息获取失败');
	}

	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$code.'&grant_type=authorization_code';

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);

	// 显示获得的数据
	// $data = '{"access_token":"ACCESS_TOKEN","expires_in":7200,"refresh_token":"REFRESH_TOKEN","openid":"OPENID","scope":"SCOPE"}';
	// $data = '{"errcode":40029,"errmsg":"invalid code"}';
	$res = json_decode($data,true);
	if(isset($res['errcode']) || strlen($res['openid'])<28){
		exit('Step2.2 用户信息获取失败');
	}
	//return $res['openid'];

	// 以下第三步，刷新access_token
	$url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.APPID.'&grant_type=refresh_token&refresh_token='.$res['refresh_token'];

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data3 = curl_exec($curl);
	curl_close($curl);
	$res3 = json_decode($data3,true);
	if(isset($res3['errcode']) || strlen($res3['openid'])<28){
		exit('Step3 刷新access_token失败');
	}

	// 以下第四步，拉取用户信息
	$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$res3['access_token'].'&openid='.$res3['openid'].'&lang=zh_CN';

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data4 = curl_exec($curl);
	curl_close($curl);
	$res4 = json_decode($data4,true);
	if(isset($res4['errcode']) || strlen($res4['openid'])<28){
		exit('Step4 拉取用户信息失败');
	}

	var_dump($res4);exit;
}
*/


/**
 * [凯信通短信接口]
 * @param  string  $phone 手机号
 * @param  string  $msg   信息
 * @param  integer $time  时间戳 或 延时秒数，用于定时发送
 * @return boolean
 */
function sendSMS($phone,$msg,$time=0){
	$msg = '【'.C('SMS_SIGN').'】'.$msg;
	$data = array('action'=>'send','userid'=>C('SMS_USERID'),'account'=>C('SMS_ACCOUNT'),'password'=>C('SMS_PASSWORD'),'mobile'=>$phone,'content'=>$msg);
	if($time>time()){
		$data['sendTime'] = date('Y-m-d H:i:s',$time);
	}elseif($time>0){
		$data['sendTime'] = date('Y-m-d H:i:s',time()+$time);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://sms.kingtto.com:9999/sms.aspx');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	$xml = curl_exec($ch);
	curl_close($ch);
	$status = false;
	if($xml && $res=simplexml_load_string($xml)){
		if($res->returnstatus=='Success' && $res->message=='ok' && $res->successCounts=='1'){
			$status = true;
		}
	}
	return $status;
}


// 密码加盐Hash
function encodePass($passSHA){
	$passSHA = strtolower($passSHA);
	$data['salt'] = strtolower(substr(uniqid(),7,6));
	$data['hash'] = strtoupper(MD5($passSHA.$data['salt']));
	return $data;
}

// 密码校验
function checkPass($passSHA,$salt,$passHASH){
	$passSHA = strtolower($passSHA);
	$salt = strtolower($salt);
	$res = strtoupper(MD5($passSHA.$salt));
	if($passHASH===$res){
		return true;
	}else{
		return false;
	}
}


function setForward(){
	$referer = I('server.HTTP_REFERER');
	$refer = parse_url($referer);
	$host = I('server.HTTP_HOST');
	if($refer['host']==$host){
		cookie('forward',$referer);
	}else{
		cookie('forward',null);
	}
}
function getForward(){
	$forward = cookie('forward');
	if(!empty($forward)){
		cookie('forward',null);
		return $forward;
	}else{
		$func = (MODULE_NAME=='Wechat') ? 'ucenter' : 'index';
		return U(MODULE_NAME.'/Index/'.$func);
	}
}


/**
 * [timeTrim 时间转化为1970时间]
 */
function timeTrim($time_original){
	$time = strtotime('1970-01-01 '.date('H:i:s',$time_original));
	if($time<0) $time = 86400 + $time;
	return $time;
}

/**
 * [timeComplete 订单结束时间修正]
 * @param  timestamp $combo      套餐结束时间
 * @param  timestamp $orderStart 当前订单开始时间
 * @return timestamp             当前订单结束时间
 */
function timeComplete($combo,$orderStart){
	return $combo + $orderStart - timeTrim($orderStart);
}

function formatToTime($time){
    $time = strtotime($time);
    $time = $time?$time:NOW_TIME;
    return $time;
}
function ma($text,$url){
    echo "<script>
 			alert('".$text."');
 			location.href='".$url."';
 		</script>
 			";
    exit;
}
function formatTime($time){
	$t = time() - $time;
	$f = array(
		'31536000' => '年',
		'2592000' => '个月',
		'604800' => '星期',
		'86400' => '天',
		'3600' => '小时',
		'60' => '分钟',
		'1' => '秒'
	);
	foreach ($f as $k => $v) {
		if (0 != $c = floor($t / (int)$k)) {
			$m = floor($t % $k);
			foreach ($f as $x => $y) {
				if (0 != $r = floor($m / (int)$x)) {
					return $c.$v.$r.$y.'前';
				}
			}
			return $c.$v.'前';
		}
	}
}

// utf-8截取字符串
function substr_utf8($str,$end,$start=0){
	if(empty($str)){
		return false;
	}
	if (function_exists('mb_substr')){
		if(func_num_args() >= 3) {
			$end = func_get_arg(2);
			return mb_substr($str,$start,$end,'utf-8');
		} else {
			mb_internal_encoding("UTF-8");
			return mb_substr($str,$start);
		}
	} else {
		$null = "";
		preg_match_all("/./u", $str, $ar);
		if(func_num_args() >= 3) {
			$end = func_get_arg(2);
			return join($null, array_slice($ar[0],$start,$end));
		} else {
			return join($null, array_slice($ar[0],$start));
		}
	}
}

/* 微信分享 BEGIN */
function JsSDK($shareimg=''){
	$base_dir = getcwd()."/Application/Common/Common/";

	$jsapiTicket = getJsApiTicket($base_dir);

	// 注意 URL 一定要动态获取，不能 hardcode.
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$timestamp = time();
	$nonceStr = createNonceStr();

	// 这里参数的顺序要按照 key 值 ASCII 码升序排序
	$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

	$signature = sha1($string);

	$signPackage = array(
		"appId"		=> APPID,
		"nonceStr"	=> $nonceStr,
		"timestamp"	=> $timestamp,
		"url"		=> $url,
		"signature"	=> $signature,
		"rawString"	=> $string
	);
	return createHTML($signPackage,$shareimg);
}

function getJsApiTicket($base_dir='') {
	// jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	$data = json_decode(file_get_contents($base_dir."jsapi_ticket.json"));
	if ($data->expire_time < time()) {
		$accessToken = getAccessToken($base_dir);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		$res = json_decode(httpGet($url));
		$ticket = $res->ticket;
		if ($ticket) {
			$data->expire_time = time() + 7000;
			$data->jsapi_ticket = $ticket;
			$fp = fopen($base_dir."jsapi_ticket.json", "w");
			fwrite($fp, json_encode($data));
			fclose($fp);
		}
	} else {
		$ticket = $data->jsapi_ticket;
	}
	return $ticket;
}

function getAccessToken($base_dir='') {
	// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	$data = json_decode(file_get_contents($base_dir."access_token.json"));
	if ($data->expire_time < time()) {
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
		$res = json_decode(httpGet($url));
		$access_token = $res->access_token;
		if ($access_token) {
			$data->expire_time = time() + 7000;
			$data->access_token = $access_token;
			$fp = fopen($base_dir."access_token.json", "w");
			fwrite($fp, json_encode($data));
			fclose($fp);
		}
	} else {
		$access_token = $data->access_token;
	}
	return $access_token;
}

function httpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);

	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}

function createNonceStr($length = 16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
}

function createHTML($sign,$shareimg=''){
	if(!empty($shareimg)){
		$url = explode('#',$sign['url']);
		if(end($url)!='wechat_redirect') $sign['url']=$url[0];
		$html = '<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script><script>wx.config({debug:false,appId:"'.$sign['appId'].'",timestamp:'.$sign['timestamp'].',nonceStr:"'.$sign['nonceStr'].'",signature:"'.$sign['signature'].'",jsApiList:["hideMenuItems","onMenuShareTimeline","onMenuShareAppMessage"]});wx.ready(function(){wx.hideMenuItems({menuList:["menuItem:share:qq","menuItem:share:weiboApp","menuItem:share:facebook","menuItem:share:QZone","menuItem:editTag","menuItem:delete","menuItem:originPage","menuItem:readMode","menuItem:openWithQQBrowser","menuItem:openWithSafari","menuItem:share:email"]});wx.onMenuShareTimeline({title:document.title,link:"'.$sign['url'].'",imgUrl:"'.$shareimg.'",success:function(){},cancel:function(){}});wx.onMenuShareAppMessage({title:document.title,desc:"",link:"'.$sign['url'].'",imgUrl:"'.$shareimg.'",type:"link",dataUrl:"",success:function(){},cancel:function(){}});});</script>';
	}else{
		$html = 'wx.config({debug:false,appId:"'.$sign['appId'].'",timestamp:'.$sign['timestamp'].',nonceStr:"'.$sign['nonceStr'].'",signature:"'.$sign['signature'].'",jsApiList:["hideMenuItems","showMenuItems","onMenuShareTimeline","onMenuShareAppMessage"]});';
	}
	return $html;
}
/* 微信分享END */


//按某字段升序排列数组
function sort_2a($array,$index){
    $total = count($array);
    for ($i=$total-1; $i>0; $i--){
        $temp      = $array[$i];
        $tempindex = $i;
        $firstval  = $temp[$index];
        for ($j=0; $j<$i; $j++){
            $secondval = $array[$j][$index];
            if ($firstval < $secondval){
                $firstval  = $secondval;
                $tempindex = $j;
            }
        }
        $array[$i]         = $array[$tempindex];
        $array[$tempindex] = $temp;
    }
    return $array;
}
//按某字段降序排列数组
function sort_2d($array,$index){
    $total = count($array);
    for ($i=$total-1; $i>0; $i--){
        $temp      = $array[$i];
        $tempindex = $i;
        $firstval  = $temp[$index];
        for ($j=0; $j<$i; $j++){
            $secondval = $array[$j][$index];
            if ($firstval > $secondval){
                $firstval  = $secondval;
                $tempindex = $j;
            }
        }
        $array[$i]         = $array[$tempindex];
        $array[$tempindex] = $temp;
    }
    return $array;
}

//获取地址
function getAddress($array,$key){
    $province = M('district')->where(array('id'=>$array[$key.'_province_id']))->find();
    $city = M('district')->where(array('id'=>$array[$key.'_city_id']))->find();
    $district = M('district')->where(array('id'=>$array[$key.'_district_id']))->find();
    if ($province['name'] == $city['name']){
        $pre_address = $city['name'].$district['name'];
    }else {
        $pre_address = $province['name'].$city['name'].$district['name'];
    }
    if ($array[$key.'_address']){
        $array[$key.'_all_address'] = $pre_address.$array[$key.'_address'];
    }else {
        $array[$key.'_all_address'] = $pre_address;
    }
    return $array;
}

/**
 * 创建和array_column函数相似的功能,由于低版本php不支持array_column
 *
 * @param  array  $array
 * @param  string $column_key
 * @param  string $index_key
 * @return array
 **/

function array_columns($array, $column_key = null, $index_key = null){
    return array_reduce($array, function ($result, $item) use ($column_key, $index_key){
        if($column_key && $index_key){
            $result[$item[$index_key]] = $item[$column_key];
        }elseif($column_key === null && $index_key){
            $result[$item[$index_key]] = $item;
        }elseif (null === $index_key && $column_key) {
            $result[] = $item[$column_key];
        }
        return $result;
    }, array());
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
        if($suffix)
             return mb_substr($str, $start, $length, $charset)."...";
        else
             return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
        if($suffix)
             return iconv_substr($str,$start,$length,$charset)."...";
        else
             return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

//截取缩略图函数
function thumb($src, $width, $height){
	$thumb_name = thumb_src($src, $width, $height);
	if(!file_exists(".".$thumb_name)){
		$c = new \Common\Model\PicModel;
		$c->thumb($src, $width, $height);
	}
	return $thumb_name;
}
//缩略图路径
function thumb_src($src, $width, $height) {
	if ($width == 0 && $height == 0) {
		return $src;
	}
	if (empty($src)) {
		$src = C('WEB_NOPIC');
	}
	$thumb = preg_replace('/\.(jpg|png|gif|jpeg)/iU', "_{$width}_{$height}.$1", $src);
	return $thumb;
}


//根据图片ID获取pic表中对应图片的方法
// function getPicPath($pic_id){
// 	$null = '';
// 	if(empty($pic_id)){
// 		return $null;
// 	}
// 	if(is_numeric($pic_id) && is_int($pic_id=$pic_id+0)){
// 		$multi = false;
// 	}elseif(is_array($pic_id)){
// 		$multi = true;
// 		$pic_ids = array_unique(array_filter(explode(',', implode(',', $pic_id))));
// 	}elseif(strpos($pic_id, ",")){
// 		$multi = true;
// 		$pic_ids = array_unique(array_filter(explode(',', $pic_id)));
// 	}else{
// 		return $null;
// 	}

// 	//cdn
// 	$cdn = C('CDN_HOST');
// 	if(empty($cdn)) $cdn = '';

// 	$db = M('pic');
// 	if(!$multi){
// 		$res = S('pic_'.$pic_id);
// 		if(empty($res)){
// 			$res = $db->where(array('pic_id'=>$pic_id))->getField('pic_path');
// 			if($res){
// 				$res = preg_replace('/^\/Public/', $cdn, $res);
// 				S('pic_'.$pic_id, $res, 86400);
// 			}else{
// 				return $null;
// 			}
// 		}
// 	}else{
// 		foreach($pic_ids as $k=>$v){
// 			$cache[$v] = S('pic_'.$v);
// 			if(!empty($cache[$v])){
// 				unset($pic_ids[$k]);
// 			}else{
// 				unset($cache[$v]);
// 			}
// 		}
// 		if(!empty($pic_ids)){
// 			$res = $db->where(array('pic_id'=>array('in',$pic_ids)))->getField('pic_id,pic_path');
// 			foreach($res as $k=>&$v){
// 				if($v){
// 					$v = preg_replace('/^\/Public/', $cdn, $v);
// 					S('pic_'.$k, $v, 86400);
// 				}else{
// 					unset($res[$k]);
// 				}
// 			}
// 			unset($v);
// 			if($cache){
// 				if($res){
// 					$res = array_merge($cache,$res);
// 				}else{
// 					$res = $cache;
// 				}
// 			}
// 		}else{
// 			$res = $cache;
// 		}
// 		if(empty($res)){
// 			return $null;
// 		}
// 	}
// 	echo $res;die;
// 	return $res;
// }

function getPicPath($data){
	$type=M('pic');
	if ($data==null){

	}else {
		if(strpos($data, ",")){
			$data = explode(",", $data);
			if($data[0]) {
				$res = $type->where('pic_id = ' . $data[0])->find();
				return $res['pic_path'];
			}
		}else{
			$re=$type->where('pic_id='.$data)->find();
			return $re['pic_path'];
		}
	}
}
/**
 * 判断字符串是否超过某个长度
 * @param $str 需要判断的字符串
 * @param $long 长度界限
 * @return array 返回判断结果
 */

function checkStrLong($str, $long){
	if(strlen($str) > $long){
		return false;
	}
	return true;
}
/**
 * 处理布尔数值返回
 */
function dealBool($val){
	return $val?1:0;
}
/**
 * 从数组中获取某个字段
 */
function getColFromArr($data,$col){
	foreach($data as $v){
		$res[] = $v[$col];
	}
	return $res;
}
/**
 * 获得订单编号
 */
 function getOrderCode($pre, $userid, $storeid){
     if ($pre == 'test'){
         $head = 'st';
     }elseif ($pre == 'sign'){
         $head = 'bm';
     }
     $code = mt_rand(100,999);
     $user = M('user')->where(array('user_id'=>$userid))->find();
     $store = M('store')->where(array('store_id'=>$userid))->find();
     return $head.$code.date('ymd').substr($user['user_number'],-2).substr($store['store_number'],-2);
 }

       // 订单导出
function xlsorder($ids){
	$path = dirname(__FILE__).'/template/';
	$tpl  = $path.'order.xlsx';
	if (!file_exists($tpl)){
		exit;
	}
	$db = M('test_order');
	// $list = M('order')->where(array('order_id'=>array('in',$ids),'disabled'=>0))->select();
	$where['test_order_is_del'] = 0;
	$where['test_order_id'] = array('in',$ids);
	$join[] = 'LEFT JOIN sx_user as b ON sx_test_order.user_id=b.user_id';
	$join[] = 'LEFT JOIN sx_test_course as c on sx_test_order.test_id=c.test_course_id';
	$join[] = 'LEFT JOIN sx_store as d on sx_test_order.store_id=d.store_id';
	$join[] = 'LEFT JOIN sx_test as a on sx_test_order.test_id=a.test_course_id';
	$list	= $db->field('sx_test_order.*, b.user_phone,c.test_course_number,c.test_course_name,d.store_name,a.test_time')->join($join)->where($where)->select();
	if(empty($list)){
		exit;
	}
		$excelTmp = PHPExcel_IOFactory::load($tpl);
		$sheet = $excelTmp->getSheet(0);
		$sheet->setTitle('试听订单');
		$excelRes = $excelTmp;


		$rows = count($list);
		if($rows>1){
			$excelRes->getActiveSheet()->insertNewRowBefore(4,$rows-1);
		}
		$paytype = C('PAY_CONFIG.type');
		$order_status = C(ORDER_STATUS);
		foreach($list as $i=>$item){
			$cur = 3 + $i;
			$excelRes->getActiveSheet()->setCellValue('A'.$cur, $item['test_order_number']);
			$excelRes->getActiveSheet()->setCellValue('B'.$cur, $item['user_phone']);
			$excelRes->getActiveSheet()->setCellValue('D'.$cur, $item['test_course_name']);
			$excelRes->getActiveSheet()->setCellValue('E'.$cur, $item['store_name']);
			$excelRes->getActiveSheet()->setCellValue('C'.$cur, date('y-m-d h:i',$item['test_time']));
			if ($item['test_order_status']==1) {
				$excelRes->getActiveSheet()->setCellValue('F'.$cur, '待核实');
			}elseif ($item['test_order_status']==2) {
				$excelRes->getActiveSheet()->setCellValue('F'.$cur, '已核实');
			}elseif ($item['test_order_status']==3) {
				$excelRes->getActiveSheet()->setCellValue('F'.$cur, '已完成');
			}elseif ($item['test_order_status']==4) {
				$excelRes->getActiveSheet()->setCellValue('F'.$cur, '已取消');
			}else{
				$excelRes->getActiveSheet()->setCellValue('F'.$cur, '已失效');
			}
			$excelRes->getActiveSheet()->setCellValue('G'.$cur, '暂无');
		}
	// 保存

	$title  = '试听订单报表';

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($excelRes, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}


/**
 * 分页格式化
 */
function pageFormatBiz($page){
	$module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
	$page->lastSuffix = false;
	$page->rollPage = 5;
	$page->setConfig('prev', '<');
	$page->setConfig('next', '>');
	$page->setConfig('last', '末页');
	$page->setConfig('first', '首页');
	$page->setConfig('num','其他');
	$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <li><span title="%TOTAL_PAGE%" class="all">共%TOTAL_PAGE%页, 到第</span><input size="6" type="text" id="z"><span>页</span><a class="tiaozhuan" href="' . $module_name . '/p/"><span>确定</span></a></li>');

	return $page;
}

function pagenew($page)
{
	$module_name = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
	$page->lastSuffix = false; // 最后一页是否显示总页数
	$page->rollPage = 5;
	$page->setConfig('prev', '上一页');
	$page->setConfig('next', '下一页');
	$page->setConfig('first', '首页');
	$page->setConfig('last', '尾页');
	$page->setConfig('theme', '<span class="right">共有<strong>%TOTAL_ROW%</strong>条记录</span> <div class="za"><span class="zi">当前第%NOW_PAGE%页</span> <span title="%TOTAL_PAGE%" class="all">共有%TOTAL_PAGE%页</span> <div class="page">%UP_PAGE% %FIRST% %link_PAGE% %END% %DOWN_PAGE%</div> <div class="info"><input size="6" type="text" id="z"><a class="tiaozhuan" href="' . $module_name . '/p/">跳转</a></div></div> ');
	return $page;
}

function returnDate($time,$g='Y-m-d'){
	return date($g,$time);
}
//时间转化
function getSec($str='08:00'){
	@list($h,$i,$s) = explode(':', $str);
	$h = ($h) ? $h : 0;
	$i = ($i) ? $i : 0;
	$s = ($s) ? $s : 0;
	return mktime($h,$i,$s,1,1,1970);
}

//验证字符串长度
function checkStrLen($str, $min = 1, $max = 80){
	$len = strlen($str);
	if($len < $min || $len > $max){
		return false;
	}
	return true;
}
//数据不为空判断
function notnull($data){
	return empty($data)?'':$data;
}
//小数格式化
function floatFormat($data, $decimal = 2){
	$data = empty($data)?0:$data;
	$data = number_format($data, $decimal, '.', '');

	return $data;
}
//验证浮点数格式是否正确
function checkFloatFormat($data){
	$filter = '/^(\d*\.)?\d+$/';
	$result = preg_match($filter, $data);

	if($result == 1){
		return  true;
	}

	return false;
}
//验证是否为正整数
function checkInt($data){
	$filter = '/^[0-9]*$/';
	$result = preg_match($filter, $data);
	if($result){
		return true;
	}else{
		return false;
	}
}

//html实体化解密
function returnHtmldecode($data){
	$back=html_entity_decode($data);
	return $back;
}

/**
 * 验证密码格式
 */
function checkPasswdFormat($passwd){
    if(empty($passwd)){
        $res = array();
        $res['res'] = 0;
        $res['msg'] = "密码不能为空";
        return $res;
    }else{
        //判断字符串长度
        $len = strlen($passwd);
        if($len > 30 || $len < 6){
            $res = array();
            $res['res'] = 0;
            $res['msg'] = '密码为6-30个字符';
            return $res;
        }
        $filter = '/^[a-zA-Z0-9_]$/';
        if(preg_match($filter, $passwd)){
            $res = array();
            $res['res'] = 0;
            $res['msg'] = '密码中包含特殊字符';
            return $res;
        }
        $res = array();
        $res['res'] = 1;
        return $res;
    }
}
/* 当前设置的时间截点 */
function cutoff_time($type = 1){
    if($type == 2){
        return 8 * 3600;   //当前的时间减去该小时数可以得到当前应显示的时间数
    }else{
        return 0;   // 1970-01-01 08:00:00;
    }
}

function getMenuList($act_list){
	//根据角色权限过滤菜单
	$menu_list = getAllMenu();
	if($act_list != 'all'){
		$right = M('system_menu')->where("id", "in", $act_list)->cache(true)->getField('right',true);
		foreach ($right as $val){
			$role_right .= $val.',';
		}
		$role_right = explode(',', $role_right);		
		foreach($menu_list as $k=>$mrr){
			foreach ($mrr['sub_menu'] as $j=>$v){
				if(!in_array($v['control'].'Controller@'.$v['act'], $role_right)){
					unset($menu_list[$k]['sub_menu'][$j]);//过滤菜单
				}
			}
		}
	}
	return $menu_list;
}

function getAllMenu(){
	return	array(
			'system' => array('name'=>'系统设置','icon'=>'fa-cog','sub_menu'=>array(
					array('name'=>'网站设置','act'=>'index','control'=>'System'),
					array('name'=>'友情链接','act'=>'linkList','control'=>'Article'),
					array('name'=>'自定义导航','act'=>'navigationList','control'=>'System'),
					array('name'=>'区域管理','act'=>'region','control'=>'Tools'),
					array('name'=>'短信模板','act'=>'index','control'=>'SmsTemplate'),
					
			)),
			'access' => array('name' => '权限管理', 'icon'=>'fa-gears', 'sub_menu' => array(
					array('name'=>'权限资源列表','act'=>'right_list','control'=>'System'),
					array('name' => '管理员列表', 'act'=>'index', 'control'=>'Admin'),
					array('name' => '角色管理', 'act'=>'role', 'control'=>'Admin'),
					array('name' => '供应商管理', 'act'=>'supplier', 'control'=>'Admin'),
					array('name' => '管理员日志', 'act'=>'log', 'control'=>'Admin'),
			)),
			'member' => array('name'=>'会员管理','icon'=>'fa-user','sub_menu'=>array(
					array('name'=>'会员列表','act'=>'index','control'=>'User'),
					array('name'=>'会员等级','act'=>'levelList','control'=>'User'),
					array('name'=>'充值记录','act'=>'recharge','control'=>'User'),
					array('name' => '提现申请', 'act'=>'withdrawals', 'control'=>'User'),
					array('name' => '汇款记录', 'act'=>'remittance', 'control'=>'User'),
					//array('name'=>'会员整合','act'=>'integrate','control'=>'User'),
			))
	);
}

function getMenuArr(){
	$menuArr = include APP_PATH.'Admin/Conf/menu.php';
	$act_list = session('act_list');
	if($act_list != 'all' && !empty($act_list)){
		$right = M('system_menu')->where(array('id'=>array('in',$act_list)))->cache(true)->getField('right',true);
		foreach ($right as $val){
			$role_right .= $val.',';
		}
		// dump($role_right);die;	
		foreach($menuArr as $k=>$val){
			foreach ($val['child'] as $j=>$v){
				foreach ($v['child'] as $s=>$son){
					if(!strpos($role_right,$son['op'].'Controller@'.$son['act'])){
						unset($menuArr[$k]['child'][$j]['child'][$s]);//过滤菜单
					}
				}
			}
		}
	
		foreach ($menuArr as $mk=>$mr){
			foreach ($mr['child'] as $nk=>$nrr){
				if(empty($nrr['child'])){
					unset($menuArr[$mk]['child'][$nk]);
				}
			}
		}
	}
	return $menuArr;
}

 // 定义一个函数getIP() 客户端IP，
function getIP(){            
    if (getenv("HTTP_CLIENT_IP"))
         $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
         $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    
    if(preg_match('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1 -9]?\d))))$/', $ip))          
        return $ip;
    else
        return '';
}
// 服务器端IP
 function serverIP(){   
  return gethostbyname($_SERVER["SERVER_NAME"]);   
 } 

 function getcity($id)
 {
 	$city = M('region')->where(array('id'=>$id))->find();
 	$province = M('region')->where(array('id'=>$city['parent_id']))->find();
 	$name = M('region')->where(array('id'=>$province['parent_id']))->find();
 	return $name['name'];
 }
 
 require_once('PHPExcel.php');
 function exportXLS($title,$head,$body,$format){
 	$objPHPExcel = new PHPExcel();
 
 	// 设置文件属性
 	$objPHPExcel->getProperties()->setCreator("forestall")
 	->setLastModifiedBy("forestall")
 	->setTitle("");
 	//  ->setSubject("phpexcel Test Document")
 	//  ->setDescription("Test document for phpexcel, generated using PHP classes.")
 	//  ->setKeywords("office 2007 openxml php c1gstudio")
 	//  ->setCategory("Test");
 
 	//设置当前活动的sheet
 	$objPHPExcel->setActiveSheetIndex(0);
 
 	//设置sheet名字
 	$objPHPExcel->getActiveSheet()->setTitle($title);
 
 	//设置默认行高
 	//$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
 
 	//设置行高
 	//$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(20);
 
 	//设置列宽
 	//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20');
 
 	//加粗
 	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
 
 	//由PHPExcel根据传入内容自动判断单元格内容类型
 	//$objPHPExcel->getActiveSheet()->setCellValue('A1', "Firstname");
 
 	// 日期
 	//$objPHPExcel->getActiveSheet()->setCellValue('G2', '2008-12-31');
 	//$objPHPExcel->getActiveSheet()->getStyle('G2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
 
 	//表头
 	foreach($head as $key=>$val){
 		$objPHPExcel->getActiveSheet()->setCellValue($key, $val);
 	}
 
 
 	//表单
 	$row = 2;
 	foreach($body as $v){
 		foreach ($format as $kk=>$vv){
 			$objPHPExcel->getActiveSheet()->setCellValue($kk.$row, $v[$vv]);
 		}
 		$row++;
 	}
 
 
 	// 固定第一行
//  	$objPHPExcel->getActiveSheet()->freezePane('A2');
 
 
 
 	// 保存
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="'.$title.date('ymd').'.xls"');
 	header('Cache-Control: max-age=0');
 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 	//$objWriter->save('php://output');
 	$filename = 'tmp'.time().'.xls';
 	$objWriter->save($filename);
 	echo file_get_contents($filename);
 	unlink($filename);
 	exit;
 }
 //生成随机数
 function bilud_code()
   {
      $aways_code = M('users')->where(array('referees_code'=>array('neq',0)))->getField('referees_code');
      $new_code = rand(0,9999);
      if (in_array($new_code,$aways_code)) {
           bilud_code();
         }else{
         	return $new_code;
         }
   }