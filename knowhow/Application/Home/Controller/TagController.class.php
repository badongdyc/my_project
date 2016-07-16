<?php
namespace Home\Controller;
use Think\Cache\Driver\Memcache;

use Think\Controller;
class TagController extends Controller {
	public function queryTag(){
		header('content-type:application/json');
		$m=D('Tag','Service');
		$this->ajaxReturn($m->showAllTag());
	}

	/**
	 * 作者		dyc
	 * 函数的描述   查询标签 问题数排序
	 * 参数列表	
	 * 返回值		array
	 * 函数被访问的接口列表
	 *		TagController
	 * */
	public function showTagByAskNum(){
		header('content-type:application/json');
		$m=D('Tag','Service');
		$this->ajaxReturn($m->showTagByAskNum());
	}

	/**
	 * 作者		dyc
	 * 函数的描述   查询标签 字母排序
	 * 参数列表	
	 * 返回值		array
	 * 函数被访问的接口列表
	 *		TagController
	 * */
	public function showTagByName(){
		header('content-type:application/json');
		$m=D('Tag','Service');
		// var_dump($m->showTagByName());die;
		$this->ajaxReturn($m->showTagByName());
	}

	
}
?>