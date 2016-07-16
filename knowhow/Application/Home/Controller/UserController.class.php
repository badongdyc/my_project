<?php
namespace Home\Controller;
use Think\Cache\Driver\Memcache;

use Think\Controller;
class UserController extends Controller {

	/**
	  * 作者		dyc
	  * 函数的描述  查询用户信息
	  * 参数列表
	  * 			$uid 用户的id
	  * 返回值	array
	  * 函数被访问的接口列表
	  * 	UserController
	  *
	  * */
	function showOneUser(){
		header('content-type:application/json');
		extract(I());
		$m=D('User','Service');
		// print_r($m->showOneUser(1));die;
		$this->ajaxReturn($m->showOneUser($uid));
	}
     

    /**
	 * 作者 dyc
	 * 函数的描述： 用户注册
	 * 参数列表$arr=>array(
	 *			username=>用户名
	 *			password=>密码
	 * )
	 * 返回值      使用ADD方法,返回新创建用户ID
	 * 函数被访问的接口列表
	 *
	 * */
	function reg(){
		header('content-type:application/json');
		extract(I());
		$m=D('User','Service');
		$regDate=getTime();
		$arr=array('username'=>$username,'password'=>$password,'Userinfo'=>array('name'=>$nickname,'regDate'=>$regDate,'lastLoginTime'=>$regDate));
		// print_r($m->reg($arr));die;
		$this->ajaxReturn($m->reg($arr));
	}

	function login(){
		header('content-type:application/json');
		$m=D('User','Service');
		$arr=I();
		$logintime=getTime();
		// print_r($m->login($arr,$logintime));
		$this->ajaxReturn($m->login($arr,$logintime));
	}
    /**
	 * 作者 dyc
	 * 函数的描述： 编辑用户信息
	 * 参数列表        $uid       用户id
	 * 		 	$arr=>Userinfo=>array(
	 *			name		=>'姓名',
	 *			website		=>'主页'(可为空)
	 *			location	=>'地区',
	 *			age			=>'年龄'
	 *			url			=>'头像地址'
	 *			des			=>'个人简介'
	 * 	 )
	 * 返回值      被影响的记录条数
	 * 函数被访问的接口列表
	 *
	 * */
	function editUserInfo(){
		header('content-type:application/json');
		$data=I();
		$uid=$data['uid'];
		unset($data['uid']);
		// var_dump($uid,$data);die;
		$m=D('User','Service');
		$this->ajaxReturn($m->modifyUserInfo($uid,$data));
	}



 
}
?>
