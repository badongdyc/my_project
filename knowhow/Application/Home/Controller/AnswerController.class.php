<?php
namespace Home\Controller;
use Think\Cache\Driver\Memcache;

use Think\Controller;
class AnswerController extends Controller {

	/**
	  * 作者		dyc
	  * 函数的描述  插入一个普通问题
	  * 参数列表
	  * 	$arr=>array(	
	  *			title varchar(255) not null '问题主题',
	  *			content text not null '问题内容',
	  *			publishTime datetime comment '发布时间',
	  *			uid int comment '用户ID',
	  * 	)
	  * 返回值	使用ADD方法，成功后返回此问题的ID
	  * 函数被访问的接口列表
	  * 	AskController
	  *
	  * */
	function answerOpt(){
		header('content-type:application/json');
		extract(I());
		$m=D('Answer','Service');
		$this->AjaxReturn($m->ansOpt($aid,$opt,$uid,$ansid));
	}

	/**
	  * 作者		dyc
	  * 函数的描述  插入一条回复
	  * 参数列表
	  * 	$arr=>array(	
	  *			askid  '问题ID',
	  *			content  '回复内容',
	  *			uid  '用户ID',
	  * 	)
	  * 返回值	使用ADD方法，成功后返回此回复的ID
	  * 函数被访问的接口列表
	  * 	AnswerService
	  * */
	function addAnswer(){
		header('content-type:application/json');
		$arr=I();
		$m=D('Answer','Service');
		$ansid=$m->createAnswer($arr);
		$rs=$m->showOneAnswer($ansid);
		$this->ajaxReturn($rs);
	}

}
?>