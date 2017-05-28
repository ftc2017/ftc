<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class EmptyController extends AdminController {

      function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
}
?>