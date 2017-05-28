<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>重置密码页面</title>
    <meta content="Coolsite360,响应式网站,CMS网站,自助建网站,手机网站,微信网站,微网站,html5网站,css3网站,网页设计,html导出,App导出,微信导出" name="keywords">
    <meta content="" name="description">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="renderer" content="webkit">

    <!--[if lt ie 9]>
      <script type="text/javascript" src="/Public/Home/thirdparty_js/html5shiv.js"></script>
      <script type="text/javascript" src="/Public/Home/thirdparty_js/selectivizr-min.js"></script>
    <![endif]-->

    <link type="text/css" href="/Public/Home/thirdparty_css/bootstrap.min.css" rel="stylesheet" media="all">
    <link type="text/css" href="/Public/Home/thirdparty_css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="http://cdn.bootcss.com/fancybox/3.0.47/jquery.fancybox.min.css" rel="stylesheet">
    <link type="text/css" href="/Public/Home/css/pack.built.2045013b.cache.css" rel="stylesheet" media="all">
    <style>
    @font-face{font-family:'Caudex';font-style:normal;font-weight:400;src:local(Caudex),url(https://fonts.gstatic.com/s/caudex/v6/6CwZGTsjYRXUiSDlabVS2n-_kf6ByYO6CLYdB4HQE-Y.woff2) format("woff2");unicode-range:U+1F00-1FFF}@font-face{font-family:'Caudex';font-style:normal;font-weight:400;src:local(Caudex),url(https://fonts.gstatic.com/s/caudex/v6/uKDHrXVo0UKVEKBdFH13fH-_kf6ByYO6CLYdB4HQE-Y.woff2) format("woff2");unicode-range:U+0370-03FF}@font-face{font-family:'Caudex';font-style:normal;font-weight:400;src:local(Caudex),url(https://fonts.gstatic.com/s/caudex/v6/M9hv5IlpxEaNftgkD8754H-_kf6ByYO6CLYdB4HQE-Y.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Caudex';font-style:normal;font-weight:400;src:local(Caudex),url(https://fonts.gstatic.com/s/caudex/v6/oK9B5EatFn1pC05ZmnkMduvvDin1pK8aKteLpeZ5c0A.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}@font-face{font-family:'Oswald';font-style:normal;font-weight:700;src:local('Oswald Bold'),local(Oswald-Bold),url(https://fonts.gstatic.com/s/oswald/v13/smkSb2csQFrK-wxLDSe5R4X0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:'Oswald';font-style:normal;font-weight:700;src:local('Oswald Bold'),local(Oswald-Bold),url(https://fonts.gstatic.com/s/oswald/v13/69aXBpgQONjr_rHWADjBuYX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0102-0103,U+1EA0-1EF9,U+20AB}@font-face{font-family:'Oswald';font-style:normal;font-weight:700;src:local('Oswald Bold'),local(Oswald-Bold),url(https://fonts.gstatic.com/s/oswald/v13/dI-qzxlKVQA6TUC5RKSb34X0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Oswald';font-style:normal;font-weight:700;src:local('Oswald Bold'),local(Oswald-Bold),url(https://fonts.gstatic.com/s/oswald/v13/bH7276GfdCjMjApa_dkG6ZBw1xU1rKptJj_0jans920.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}@font-face{font-family:'Playfair Display';font-style:normal;font-weight:400;src:local('Playfair Display'),local(PlayfairDisplay-Regular),url(https://fonts.gstatic.com/s/playfairdisplay/v10/2NBgzUtEeyB-Xtpr9bm1CRw5vVFbIG7DatP53f3SWfE.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:'Playfair Display';font-style:normal;font-weight:400;src:local('Playfair Display'),local(PlayfairDisplay-Regular),url(https://fonts.gstatic.com/s/playfairdisplay/v10/2NBgzUtEeyB-Xtpr9bm1CSVudZg2I_9CBJalMPResNk.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Playfair Display';font-style:normal;font-weight:400;src:local('Playfair Display'),local(PlayfairDisplay-Regular),url(https://fonts.gstatic.com/s/playfairdisplay/v10/2NBgzUtEeyB-Xtpr9bm1CRD8Ne_KjP89kA3_zOrHj8E.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/ty9dfvLAziwdqQ2dHoyjphkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+0460-052F,U+20B4,U+2DE0-2DFF,U+A640-A69F}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/frNV30OaYdlFRtH2VnZZdhkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/gwVJDERN2Amz39wrSoZ7FxkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+1F00-1FFF}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/aZMswpodYeVhtRvuABJWvBkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+0370-03FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/VvXUGKZXbHtX_S_VCTLpGhkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+0102-0103,U+1EA0-1EF9,U+20AB}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/e7MeVAyvogMqFwwl61PKhBkAz4rYn47Zy2rvigWQf6w.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:100;src:local('Roboto Thin'),local(Roboto-Thin),url(https://fonts.gstatic.com/s/roboto/v16/2tsd397wLxj96qwHyNIkxHYhjbSpvc47ee6xR_80Hnw.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/0eC6fl06luXEYWpBSJvXCIX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0460-052F,U+20B4,U+2DE0-2DFF,U+A640-A69F}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/Fl4y0QdOxyyTHEGMXX8kcYX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/-L14Jk06m6pUHB-5mXQQnYX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+1F00-1FFF}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/I3S1wsgSg9YCurV6PUkTOYX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0370-03FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/NYDWBdD4gIq26G5XYbHsFIX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0102-0103,U+1EA0-1EF9,U+20AB}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/Pru33qjShpZSmG3z6VYwnYX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:300;src:local('Roboto Light'),local(Roboto-Light),url(https://fonts.gstatic.com/s/roboto/v16/Hgo13k-tfSpn0qi1SFdUfZBw1xU1rKptJj_0jans920.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/s7gftie1JANC-QmDJvMWZoX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0460-052F,U+20B4,U+2DE0-2DFF,U+A640-A69F}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/3Y_xCyt7TNunMGg0Et2pnoX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/WeQRRE07FDkIrr29oHQgHIX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+1F00-1FFF}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/jyIYROCkJM3gZ4KV00YXOIX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0370-03FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/phsu-QZXz1JBv0PbFoPmEIX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0102-0103,U+1EA0-1EF9,U+20AB}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/9_7S_tWeGDh5Pq3u05RVkoX0hVgzZQUfRDuZrPvH3D8.woff2) format("woff2");unicode-range:U+0100-024F,U+1E00-1EFF,U+20A0-20AB,U+20AD-20CF,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:'Roboto';font-style:normal;font-weight:900;src:local('Roboto Black'),local(Roboto-Black),url(https://fonts.gstatic.com/s/roboto/v16/mnpfi9pxYH-Go5UiibESIpBw1xU1rKptJj_0jans920.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215}
</style>

    

    

    <script>
    coolsite360={
        PlayerPlugins:[]
    } ;
    </script>
    <link type="text/css" href="/Public/Home/global_main.css" rel="stylesheet">
    <!--[if lte IE 8]>
      <link type="text/css" href="/Public/Home/styles_forie.css" rel="stylesheet">
      <script type="text/javascript" src="/Public/Home/thirdparty_js/respond.src.js"></script>
    <![endif]-->

    <script type="text/javascript">
      if(navigator.appVersion.match(/MSIE [678]./i)==null){
        document.writeln('<link type="text/css" href="/Public/Home/styles.css" rel="stylesheet">')
      }
    </script>
    
    <script type="text/javascript" src="/Public/Home/js/chongzhimima_data.js"></script>
    <script type="text/javascript" src="/Public/Home/thirdparty_js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/Public/Home/thirdparty_js/bootstrap.min.js"></script>




</head>

<body class="body_CnyKxc" data-c_tl_id="M_60afac8400e0032c">
 <div class="container-fluid c-section section_Wl9Fwc" data-c_tl_id="tl_f0901961c7d48a6739beb995b48eff8c" id="section29e0c944">
  <div class="c-div div_d5ltqK">
   <div class="container c-container container_1oVg7Q signup">
    <div class="row hidden-xs c-row row_Kjbz8z"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 c-column column_mvZe5k">
      <img class="c-image image_JAIjci" src="/Public/Home/files/5d54717483ef28a4c818ec25b3ee333e.png"/>
      <div class="textblock_sQAlIW">
       <p>
        <span style="font-size: 14pt; color: #ffffff;">
         创世宣言：
        </span>
       </p>
       <p>
        <span style="color: #c0c0c0;">
         权益凭证和货币是人类社会价值交易的基础支撑，权益凭证确定了资产归属，货币促进了权益归属过程的完成。自由贸易离不开信任，全新的信任机制必将全面重构贸易逻辑。而去中心化的信任机制将全新构建个人与组织、组织与组织、个人与政府的合作关系和协同机制，提高交易速度、减少交易风险、降低交易成本。区块链技术理念的出现将保证去中心化信任机制的实现，未来还将有新的理念不断出现，推动人类社会不断向前，推动经济不断发展，推动人与环境和谐共处，推动人类远离金融经济危机。
        </span>
       </p>
      </div>
     </div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 c-column column_EXJ39w signup">
      <p class="paragraph_ziD2v8">
       第二步：重置FTC帐户密码：
      </p>
      <div class="c-div div_5TnKeY">
       <form action="" class="c-form form_mgltEd" data-redirect="/success" method="post" way="ajax">
        <label class="c-label label_3UG6Ic">
         新密码:
        </label>
        <input type="hidden" name="mobile" value="<?php echo ($mobile); ?>">
        <input class="c-input input_8FlkY6 signup" name="password" placeholder="请输入密码" required="True" type="password"/>
        <label class="c-label label_3UG6Ic">
         确认新密码:
        </label>
        <input class="c-input input_8FlkY6 signup" name="repassword" placeholder="请输入密码" required="True" type="password"/>
        <div class="checkbox c-checkbox checkbox_HIxYhD">
        </div>
        <button class="submit_n7xCQ7" name="" type="submit" wait="">
         提交
        </button>
        <a class="btn c-button submit_n7xCQ7 regi" href="<?php echo U('Account/login');?>" >
         登录
        </a>
       </form>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
</body>


<script>
    var page_slug = 'chongzhimima';
</script>


<!-- ie8 以下版本提示信息 -->
<!--[if lt ie 8]>
  <script type="text/javascript">
    window.onload=function(){
      document.getElementById('browser-tip').style.display = "block";
      document.getElementsByTagName('body')[0].style.paddingTop = "50px";
    }
    function hide(){
      document.getElementById('browser-tip').style.display = "none";
      document.getElementsByTagName('body')[0].style.paddingTop = "0px";
    }
  </script>
<![endif]-->
<div id="browser-tip" style="position:fixed; top:0; left:0; right:0; z-index:9999; height:30px; line-height:33px; padding:10px 0 9px; text-indent:30px; font-size:15px; background:#fcf8e3; border-bottom:1px solid #fbeed5; width:100%;display:none;">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block; float:right; z-index:9999; line-height:36px; margin-right: 0px;padding:0; font-size:32px; color:#666; box-shadow: 0; text-shadow:none; text-align:center; filter:alpha(opacity=100); -khtml-opacity:1; opacity:1;" onclick="hide()">×</button>
  <p style="text-align: center;">
    您当前使用的浏览器版本较低，为使您获得更好的操作体验，我们推荐您使用：
    <span style="margin-left:10px;">
      <a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank">
        <img src="http://qn.static.epub360.com/site-epub360/img/compatible_ie.gif" width="31" height="30" alt="Internet Explorer" title="Internet Explorer"></a>
      <a href="http://www.google.com/intl/zh-CN/chrome/" target="_blank">
        <img src="http://qn.static.epub360.com/site-epub360/img/compatible_chrome.gif" width="31" height="30" alt="Google Chrome" title="Google Chrome"></a>
      <a href="http://www.mozilla.org/zh-CN/firefox/new/" target="_blank">
        <img src="http://qn.static.epub360.com/site-epub360/img/compatible_firefox.gif" width="31" height="30" alt="Firefox" title="Firefox"></a>
      <a href="http://support.apple.com/zh_CN/downloads/#safari" target="_blank">
        <img src="http://qn.static.epub360.com/site-epub360/img/compatible_safari.gif" width="28" height="30" alt="Safari" title="Safari"></a>
    </span>
  </p>
</div>
<script type="text/javascript" src="http://cdn.bootcss.com/jarallax/1.7.2/jarallax.min.js"></script>
<script src="http://cdn.bootcss.com/headroom/0.9.3/headroom.min.js"></script>
<script src="http://cdn.bootcss.com/headroom/0.9.3/jQuery.headroom.min.js"></script>
<script src="http://cdn.bootcss.com/fancybox/3.0.47/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="/Public/Home/js/vendor_c.bundle.built.e43e22b1.cache.js"></script>
<script type="text/javascript" src="/Public/Home/js/pack.built.f66be826.cache.js"></script>
<script type="text/javascript" src="/Public/Home/js/cst.built.438db8a8.cache.js"></script>
<script type="text/javascript" src="/Public/Home/js/comment.js"></script>
<script type="text/javascript" src="/Public/Home/static/layer/layer.js"></script>


</html>