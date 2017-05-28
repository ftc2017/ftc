<?php
namespace Admin\Controller;
class ArticleController extends AdminController {
	//文章分类管理
	public function classify_lists(){
		$T_ArticleClass=M('article_classify');
		$this->ArticleClass_list=parent::getEval(parent::pageS($T_ArticleClass,100,'article_classify_sort desc'),'article_classify_id','article_classify_name','article_classify_pid','article_classify_eval');
		$this->display();
	}
	public function classify_edit(){

		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。','',true);

		$T_ArticleClass=M('article_classify');
		if (!empty($parm['id'])) {
			$this->ArticleClass_info=$T_ArticleClass->where(array('article_classify_id'=>$parm['id']))->find();
		}
		$this->ArticleClass_list=parent::getEval(parent::pageS($T_ArticleClass,100,'article_classify_sort desc'),'article_classify_id','article_classify_name','article_classify_pid','article_classify_eval');
		$this->display();
	}
	public function classify_editok(){

		$parm = I('post.');
		parent::verify_parm($parm,array('article_classify_name','article_classify_sort')) || $this->error('参数错误。。。','',true);

		$T_ArticleClass=M('article_classify');

		if (!empty($parm['article_classify_pid'])) {
			$ArticleClass_info = $T_ArticleClass->where(array('article_classify_id'=>$parm['article_classify_pid']))->find();
			if (empty($ArticleClass_info)) {
				$this->error('所属分类不存在','');
			}
			$article_classify_eval = ($ArticleClass_info['article_classify_eval']+1);
		}else{
			$article_classify_eval = 1;
		}
		$T_ArticleClass->data(array());
		$T_ArticleClass->create();
		$T_ArticleClass->article_classify_eval = $article_classify_eval;
		if (empty($parm['id'])) {
			$T_ArticleClass->article_classify_createtime=time();
			$res=$T_ArticleClass->add();
		}else{
			$res=$T_ArticleClass->where(array('article_classify_id'=>$parm['id']))->save();
		}
		$res || $this->error('服务器正忙。。。','');
		$this->success('操作成功',U('Article/classify_lists'));
	}
	public function classify_del(){

		$parm = I('get.');
		parent::verify_parm($parm,array('id'),array('id'=>'/^\d[0-9]{1,11}$/')) || $this->error('参数错误。。。','',true);

		M('article_classify')->where(array('article_classify_id'=>$parm['id']))->delete() || $this->error('服务器正忙。。。','',true);
		$this->success('操作成功',U('Article/classify_lists'));
	}
	public function classify_editall(){

	}
	//文章管理
	public function lists(){
		$T_Article = M('article');
		$T_ArticleClass = M('article_classify');//
		$this->Article_list = $T_Article->join('LEFT JOIN cs_article_classify ON cs_article.article_classify_id=cs_article_classify.article_classify_id')->where(array('is_del'=>0))->select();
// 		$this->ArticleClassify_list = parent::getEval(parent::pageS($T_ArticleClass,10,'article_classify_sort desc'),'article_classify_id','article_classify_name','article_classify_pid','article_classify_eval');
		$this->display();
	}
	public function edit(){

		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。','',true);

		$T_Article = M('article');
		$T_ArticleClass = M('article_classify');
		$this->ArticleClassify_list = parent::getEval(parent::pageS($T_ArticleClass,100,'article_classify_sort desc'),'article_classify_id','article_classify_name','article_classify_pid','article_classify_eval');
		if (!empty($parm['id'])) {
			$Article_info = $T_Article->where(array('article_id'=>$parm['id'],'is_del'=>0))->find();
			if (empty($Article_info)) {
				$this->error('文章不存在');
			}
			$this->Article_info = $Article_info;
		}
		$this->display();
	}
	public function editok(){

		$parm = I('post.');
		parent::verify_parm($parm) || $this->error('参数错误。。。');

		M('article_classify')->where(array('article_classify_id'=>$parm['article_classify_id']))->find() || $this->error('分类不存在','');
		
		$T_Article = M('article');
		$T_Article->create();
		if (empty($parm['id'])) {
			$type = M('article_classify')->where(array('article_classify_id'=>$parm['article_classify_id']))->find();
			if($type['article_classify_type']==1){
				$rest = M('article')->where(array('article_classify_id'=>$parm['article_classify_id']))->find();
	// 			dump($rest);exit;
				if($rest){
					$this->error('此分类类型为单页，请修改原有添加的文章',U('Admin/Article/edit/id/'.$rest['article_id']));
				}
			}
			$T_Article->article_createtime = time();
			$T_Article->add_admin_id = 1;
			$res=$T_Article->add();
		}else{
			$res=$T_Article->where(array('article_id'=>$parm['id']))->save();
		}
		$res || $this->error('服务器正忙。。。',U('Article/lists'));
		$this->success('操作成功',U('Article/lists'));
	}
	public function del(){

		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。','',true);

		M('article')->where(array('article_id'=>$parm['id']))->setField('is_del',1) || $this->error('服务器正忙。。。','');
		$this->success('操作成功',U('Article/lists'));
	}
	
	public function articleDel(){
		M('article')->where(array('article_id'=>array('in',I('id'))))->setField('is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
	
	public function articleClassifyDel(){
		M('article_classify')->where(array('article_classify_id'=>array('in',I('id'))))->setField('article_classify_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}

	public function editall(){

	}
}