<?php
namespace Home\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel {
	protected $_validate = array(
			array('username','require','用户名不能为空',1,'',2),
			array('password','require','密码不能为空',1,'',2),
			array('username','checkname','帐号不存在！',1,'callback',2),
			array('password','checkpsw','密码错误',1,'callback',2),
			
			array('username','','帐号名称已经存在！',1,'unique',	4),
			array('username','1,6','帐号长度不合法！',1,'length',4),
			array('password','6,30','密码长度不合法！',1,'length',4)
	);

	function checkname(){
		$username=I("post.username");
		$arr= $this->query("select * from t_user where username='$username'");
		if(count($arr)==0){
			return false;
		}
	}
	function checkpsw(){
		$password=I("post.password");
		$username=I("post.username");
		$arr= $this->query("select * from t_user where username='$username' and password='$password'");
		if(count($arr)==0){
			return false;
		}
	}
	
	protected $_link = array(
		#用户信息
		"Userinfo"=> array(
			"mapping_type" => self::HAS_ONE,
			"foreign_key"  => "id"		
		),
		#用户 对应的问题
		"Ask" => array(
			"mapping_type" => self::HAS_MANY,
			"foreign_key"  => "uid"
		),
		#用户 对应的回答
		"Answer" => array(
				"mapping_type" => self::HAS_MANY,
				"foreign_key"  => "uid"
		),
		#用户对问题的编辑
		"Askinfo" => array(
				"mapping_type" => self::HAS_MANY,
				"foreign_key"  => "uid"
		),
		#用户创建的标签
		"Tag" => array(
				"mapping_type" => self::HAS_MANY,
				"foreign_key"  => "uid"
		),
		'badges'=>array(
				'mapping_type' => self::MANY_TO_MANY,
				'foreign_key' => 'uid',
				'relation_foreign_key' => 'bid',
				'relation_table' => 't_user_badges' 
		)
			
	);
}

?>