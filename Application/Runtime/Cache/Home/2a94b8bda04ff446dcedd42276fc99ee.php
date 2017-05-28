<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>我的帐户主页</title>
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
    
    <script type="text/javascript" src="/Public/Home/js/accountspace_data.js"></script>
    <script type="text/javascript" src="/Public/Home/thirdparty_js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/Public/Home/thirdparty_js/bootstrap.min.js"></script>




</head>
<body class="body_FP8sWk" data-c_tl_id="M_ebf1e60d7e3a83a6">
    <div class="c-div div_J9MP6S">
  <nav class="navbar c-navbar navbar_ev1lH4">
   <div class="container-fluid navcontainer_coyyEt">
    <div class="navbar-header c-navheader navheader_zi2nZC">
     <button class="navbar-toggle c-navmenubutton navmenubutton_53QFzk" data-target="#navmenu_92dcef63aa0063db" data-toggle="collapse" type="button">
      <span class="sr-only c-span span_4iV6j0">
       Toggle navigation
      </span>
      <span class="icon-bar c-nav-btn-span span_62ZrWp">
      </span>
      <span class="icon-bar c-nav-btn-span span_ERHyuA">
      </span>
      <span class="icon-bar c-nav-btn-span span_7meUXn">
      </span>
     </button>
     <a class="c-linkblock linkblock_lt9fqD" href="#">
      <img class="c-image image_UYRfNn" src="/Public/Home/files/36b2574273642734b937dddc1901d699.png"/>
     </a>
    </div>
    <div class="collapse navbar-collapse c-navcollapse navcollapse_WPenC0" id="navmenu_92dcef63aa0063db">
     <ul class="nav navbar-nav navbar-left c-navlist navlist_sxcRyD">
      <li class="c-navlistitem navlistitem_0EiUNg">
       <a class="c-navlink navlink_mQpNCY" href="<?php echo U('Member/jczl');?>">
        帐户首页
       </a>
      </li>
      <li class="c-navlistitem navlistitem_ijnML5">
       <a class="c-navlink navlink_mQpNCY" href="<?php echo U('Member/jczl');?>">
        帐户信息
       </a>
      </li>
      <li class="c-navlistitem navlistitem_TezSnf">
       <a class="c-navlink c-initHide navlink_mQpNCY" href="#">
        注册宗族
       </a>
      </li>
      <li class="c-navlistitem navlistitem_TezSnf">
       <a class="c-navlink navlink_mQpNCY" href="#">
        注册企业
       </a>
      </li>
      <li class="c-navlistitem navlistitem_65nffO">
       <a class="c-navlink navlink_mQpNCY" href="#">
        我的履历
       </a>
      </li>
      <li class="c-navlistitem navlistitem_pvWFEd">
       <a class="c-navlink navlink_mQpNCY" data-c_act_id="M_a38fdb2cef0a5474" data-c_e_id="navlink_6d6e2327" href="#">
        我的工作
       </a>
      </li>
      <li class="c-navlistitem navlistitem_QkGhzf">
       <a class="c-navlink c-initHide navlink_mQpNCY" href="#">
        应用市场
       </a>
      </li>
      <li class="c-navlistitem navlistitem_YpIz1Q">
       <a class="c-navlink navlink_mQpNCY" href="#">
        帮助
       </a>
      </li>
     </ul>
    </div>
   </div>
  </nav>
  <nav class="navbar c-navbar navbar_dh7Bjt">
   <div class="container-fluid navcontainer_4OUIsH">
    <div class="collapse navbar-collapse c-navcollapse navcollapse_m51txL" id="navmenu_f101d7d5b7335b51">
     <ul class="nav navbar-nav navbar-right c-navlist navlist_qKGDOV">
      <li class="c-navlistitem navlistitem_31qVmx">
       <a class="c-navlink navlink_rDLT5M" href="messages.html">
        通知
       </a>
      </li>
      <li class="c-navlistitem navlistitem_4nm5rH">
       <a class="c-navlink navlink_rDLT5M" href="#">
        任务
       </a>
      </li>
      <li class="c-navlistitem navlistitem_bcxt8p">
       <a class="c-navlink navlink_rDLT5M" href="#">
        日程
       </a>
      </li>
      <li class="dropdown c-dropdown c-dropdown-hover navdropdown_miWvhD">
       <a class="dropdown-toggle navlink_rDLT5M" data-toggle="dropdown" type="button">
        <span class="droptextspan_Mpipwc">
         下午好！ERIC
        </span>
        <span class="caret c-span span_wcJbbS">
        </span>
       </a>
       <ul class="dropdown-menu c-dropdown-menu c-dropdown-menu-hover dropdownmenu_Bd76KR" role="menu">
        <li class="c-dropdown-listitem dropdownlistitem_MNy95N" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="#" role="menuitem" tabindex="-1">
          工作空间
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_wBEiWT" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="<?php echo U('member/money_list');?>" role="menuitem" tabindex="-1">
          私人空间
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_Ju1yGV" role="presentation">
         <a class="hidden-sm hidden-xs c-dropdown-textlink dropdowntextlink_xD5nkD" href="<?php echo U('member/money_list');?>" role="menuitem" tabindex="-1">
          我的帐户
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_tQAwgW" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="#" role="menuitem" tabindex="-1">
          社区
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_IaiwMX" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="#" role="menuitem" tabindex="-1">
          使用手册
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_Vc9hnn" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="page12.html" role="menuitem" tabindex="-1">
          帮助
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_KvegWV" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="#" role="menuitem" tabindex="-1">
          支持者
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_fgqJwB" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD" href="" role="menuitem" tabindex="-1">
          版本说明
         </a>
        </li>
        <li class="c-dropdown-listitem dropdownlistitem_CBcUxj" role="presentation">
         <a class="c-dropdown-textlink dropdowntextlink_xD5nkD-1" href="index.html" role="menuitem" tabindex="-1">
          退出
         </a>
        </li>
       </ul>
      </li>
     </ul>
    </div>
   </div>
  </nav>
 </div>
 <div class="c-div div_wcdd1p">
  <h1 class="c-heading heading_PMmIk5">
   欢迎您加入FTC！
  </h1>
  <p class="c-paragraph paragraph_mqOebG">
   本系统目前只开放了帐户管理系统，您的注册信息将成为之后使用FTC系统的唯一凭证，请谨慎填写您的信息，因为您的行为将会成为您未来在FTC体系里面信用的组成部分。
   <br/>
   <br/>
   接下来，开始让我们逐步了解FTC体系将会是如何运转，并将带给每一位用户什么样的价值。
   <br/>
  </p>
  <div class="textblock_uMxBtM">
   <p style="text-align: right;">
    <strong>
     <span style="font-size: 18pt;">
      FTC项目团队全体成员
     </span>
    </strong>
   </p>
   <p style="text-align: right;">
    <span style="font-size: 14pt;">
     2017年05月15日
    </span>
   </p>
  </div>
 </div>

</body>


<script>
    var page_slug = 'accountspace';
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


</html>