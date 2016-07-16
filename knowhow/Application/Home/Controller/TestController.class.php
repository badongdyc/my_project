<?php
namespace Home\Controller;
use Common\CommonController;
class TestController extends CommonController{
		function index(){
			$this->display('Ans');
		}
		
		public function aaa(){
// 			$this->display('aaa');
			print_r(C("rights"));
		}
		function Down(){
// 		 	echo I("SERVER.PATH_INFO");
		 	$a=array_pop( explode('::',(__METHOD__)) );
			if($this->checkRights($a)){
				echo '我可以执行';
			}else {
				$rs=$this->getGrade($a);
				$this->ajaxReturn($rs);
			}
				
		}
		function insertAns(){
			$content=I('post.area');
			dump(I('post.area'));
			$arr=array('content'=>$content,'uid'=>3,'askid'=>4);
			$m=D('Answer','Service');
			echo $m->createAnswer($arr);
		}
		
		function queryAns(){
			$m=D('Answer','Service');
			$arr=array('askid'=>2,'order'=>'zan-cai');
			print_r($m->showAnswer($arr));
		}
		function queryAnsByUser(){
			$m=D('Answer','Service');
			print_r($m->showAnswerByUser(3));
		}
		function updateAns(){
			$m=D('Answer','Service');
			$arr=array('uid'=>3,'id'=>3,'content'=>'test');
			print_r($m->modifyAnswer($arr));
		}
		
		function updateAnsZan(){
			$m=D('Answer','Service');
			
			print_r($m->zanAnswer(3));
		}
		function reg(){
			$m=D('User','Service');
			$regDate=date('Y-m-d H:i:s');
			$arr=array('username'=>'test','password'=>123,'Userinfo'=>array('name'=>null,'regDate'=>$regDate,'lastLoginTime'=>$regDate));
			print_r($m->reg($arr));
		}
		function login(){
			$m=D('User','Service');
			$arr=array('username'=>'dyc','password'=>111);
			$logintime=date('Y-m-d H:i:s');
			print_r($m->login($arr,$logintime));
		}

		function UserInfo(){
			$m=D('User','Service');
			$arr=array('name'=>'bbb','website'=>'www.bbb.com','location'=>'中国,新疆','age'=>25,'url'=>null,'des'=>'ahahah哈');
			print_r($m->modifyUserInfo(5,$arr));
		}
}

?>