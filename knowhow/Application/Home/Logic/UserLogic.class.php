<?php
namespace Home\Logic;

use Think\Model\RelationModel;

class UserLogic extends RelationModel {
	/**
	 * 作者 dyc
	 * 函数的描述： 用户注册
	 * 参数列表        $arr=>array(
	 *			username=>用户名
	 *			password=>密码
	 * )
	 * 返回值      使用ADD方法,返回新创建用户ID
	 * 函数被访问的接口列表
	 *
	 * */
	function insertUser($arr){
		$m=D('User');
		if($m->create($arr,4)){
			$uid= $m->relation('Userinfo')->add($arr);
			if($uid){
				session('uid',$uid);
				return $uid;
			}else{
				return false;
			}
		}else {
			return $m->getError();
		}
	}
	/**
	 * 作者 dyc
	 * 函数的描述： 用户登录
	 * 参数列表        $arr=>array(
	 *			username=>用户名
	 *			password=>密码
	 * )
	 * 			$logtime 登录时间
	 * 返回值           array
	 * 函数被访问的接口列表
	 *
	 * */
	function queryUser($arr,$logtime){
		$m=D('User');
		$rs=$m->where($arr)->relation('Userinfo')->select();
		$uid=$rs[0]['id'];
		if(count($rs)>0){
			session('uid',$uid);
			$rs1=$this->execute("update t_userinfo set lastLoginTime='$logtime' where id=$uid");
			if($rs1){
				return $rs;
			}else{
				return false;
			}
			
		}else {
			return false;
		}
	}
	
	/**
	 * 作者 dyc
	 * 函数的描述： 编辑用户信息
	 * 参数列表        $uid       用户id
	 * 			$arr=>Userinfo=>array(
	 *			name		=>'姓名',
	 *			website		=>'主页'(可为空)
	 *			location	=>'地区',
	 *			age			=>'年龄'
	 *			url			=>'头像地址'
	 *			des			=>'个人简介'
	 * 	 )
	 * 返回值         被影响的记录条数
	 * 函数被访问的接口列表
	 *
	 * */
	function updateUserInfo($uid,$arr){
		$m=M('Userinfo');
		return $m->where("id=$uid")->save($arr);
	}
	
	/**
	 * 作者 dyc
	 * 函数的描述：  查询所有用户
	 * 参数列表		null
	 * 返回值          Array
	 * 函数被访问的接口列表
	 *		UserController
	 * */
	function queryAllUser(){
		$m=M('Userinfo');
		return $m->select();
	}
	
	/**
	 * 作者 dyc
	 * 函数的描述：  查询单个用户的信息(包括:包括勋章，个人信息，)
	 * 参数列表          $uid=>用户ID
	 * 返回值              Array
	 * 函数被访问的接口列表
	 *		UserController
	 * */
	function queryOneUser($uid){
		$m=D('User');
		$arr=$m->where("id=$uid")->relation(true)->select();
		$arr[0]['asknum']=count($arr[0]['Ask']);
		$arr[0]['ansnum']=count($arr[0]['Answer']);
		return $arr;
	}
	
	/**
	 * 作者		 dyc
	 * 函数的描述：  注销登录
	 * 参数列表	 $id=>用户ID
	 * 返回值              Array
	 * 函数被访问的接口列表
	 *		UserController
	 * */
	function logout(){
		session('uid',null);
		return '退出登录成功';
	}
}

?>