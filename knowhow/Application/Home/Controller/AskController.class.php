<?php
namespace Home\Controller;
use Think\Cache\Driver\Memcache;

use Think\Controller;
class AskController extends Controller {

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
	function addAsk(){
		header('content-type:application/json');
		extract(I());
		$arr=array('title'=>$title,'content'=>$content,'uid'=>$uid,'tag'=> array('tagid'=>$tagid));
		// $arr=I();
		$m=D('Ask','Service');
		$askid=$m->createAsk($arr);
		$this->ajaxReturn($m->showAllAsk('newest',$askid));
	}
	/**
	  * 作者		dyc
	  * 函数的描述  查询所有问题
	  * 参数列表
	  * 	$order  排序规则
	  * 返回值	array
	  * 函数被访问的接口列表
	  * 	AskController
	  *
	  * */
	function queryAllAsk(){
		header('content-type:application/json');
		$m=D('Ask','Service');
		extract(I());
		$this->AjaxReturn($m->showAllAsk($order));
	}

	/**
	  * 作者		dyc
	  * 函数的描述  查询问题详情
	  * 参数列表
	  * 	$arr=>array(	
	  *			$askid 问题id
	  *			$order 排序规则
	  * 	)
	  * 返回值	arrary
	  * 函数被访问的接口列表
	  * 	AskController
	  *
	  * */
	function queryAskinfo(){
		header('content-type:application/json');
		extract(I());
		$m=D('Ask','Service');
		$ans=D('Answer','Service');
		$arr=array("askid"=>$askid,'order'=>$order);
		$answer=$ans->showAnswer($arr);
		// var_dump($answer);die;
		$ask=$m->showOneAsk($askid);
		$ask['answers']=$answer;
		$ask['answernum']=count($answer);	
		// $rs=array_merge_recursive($ask,$answer);
		$this->AjaxReturn($ask);
	}

	/**
	  * 作者		dyc
	  * 函数的描述  对问题的赞踩
	  * 参数列表
	  * 	$aid 	问题id
	  * 	$opt    赞踩操作
	  * 	$uid    用户id
	  * 返回值		布尔值
	  * 函数被访问的接口列表
	  * 	AskController
	  *
	  * */
	function askOpt(){
		header('content-type:application/json');
		extract(I());
		$m=D('Ask','Service');
		$this->AjaxReturn($m->upAsk($aid,$opt,$uid));
	}
	/**
	 * 作者		dyc
	 * 函数的描述  修改问题的浏览量
	 * 参数列表
	 *        $askid 问题id
	 * 返回值	问题id
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	
	public function upAskView(){
		header('content-type:application/json');
		extract(I());
		$ask=D('Ask','Service');
		$this->AjaxReturn($ask->upAskView($askid));
	}

    /**
	 * 作者		   dyc
	 * 函数的描述  查询某个标签对应的问题
	 * 参数列表
	 *        $askid 问题id
	 * 返回值	问题id
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	public function showAskByTag(){
		header('content-type:application/json');
		extract(I());
		$m=D('Ask','Service');
		// print_r(($m->showOneTag(1)));die;
		$this->ajaxReturn($m->showOneTag($tagid));
	}

	/**
	 * 作者		    dyc
	 * 函数的描述   根据用户id获取所有问题
	 * 参数列表		$uid=>用户id
	 * 返回值		array
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function showAskByUser(){
		header('content-type:application/json');
		extract(I());
		$m=D('Ask','Service');
		$ans=D('Answer','Service');
		$answer=$ans->showAnswerByUser($uid);
		$ask=$m->showAskByUser($uid);
		// print_r($ans->showAnswerByUser(1));die;
		$answer[0]['ask']=$ask;
		// print_r($answer);die;
		$this->ajaxReturn($answer);
	}

}