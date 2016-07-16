<?php
namespace Home\Logic;
use Think\Model\RelationModel;

class AnswerLogic extends RelationModel {
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
	function insertAnswer($arr){
		$m=D('Answer');
		$aid=$arr['askid'];
		$time=getTime();
		if($m->create($arr)){
			$rs=$this->execute("update t_ask set answerTime='$time'	where id=$aid");
			if($rs){
				return $m->add();
			}else{
				return $m->getError();
			}
		}else {
			return $m->getError();
		}
	}

	/**
	 * 作者		dyc
	 * 函数的描述    根据askid查询回复(需要分页)
	 * 参数列表	array( 
	 * 			$askid=>问题ID
	 * 			$showpage=>当前显示的页数
	 * 			$perPage=>一页显示几条记录
	 * 			$order =>排序规则
	 * )
	 * 返回值		当前问题所有回复的集合
	 * 函数被访问的接口列表
	 *		AnswerService
	 * */
	public function queryAnswer($arr){
		extract($arr);
		$m=D('Answer');
		$note=D('Ansnote');
		$arr2=array();
		$arr=$m->where("askid=$askid")->relation(true)->order("$order desc")->select();
		foreach ($arr as $key=>&$v){
			format($v['zan']);
			format($v['cai']);
			$v['vote'] = $v['zan']-$v['cai'];
			foreach($v['ansnotes'] as $v1){
				$ansid=$v['id'];
				$v['ansnotes']=$note->where("ansid=$ansid")->relation('userinfo')->select();
			}
			array_push($arr2,$v['vote']);
		}
		return $arr;
	}
	/**
	 * 作者		dyc
	 * 函数的描述    根据ansid查询回复
	 * 参数列表	
	 *          $ansid 回复id
	 * 返回值		当前问题所有回复的集合
	 * 函数被访问的接口列表
	 *		AnswerService
	 * */
	public function queryOneAnswer($ansid){
		$m=D('Answer');
		$note=D('Ansnote');
		$arr2=array();
		$arr=$m->where("id=$ansid")->relation(true)->select();
		foreach ($arr as $key=>&$v){
			format($v['zan']);
			format($v['cai']);
			$v['vote'] = $v['zan']-$v['cai'];
			foreach($v['ansnotes'] as $v1){
				$ansid=$v['id'];
				$v['ansnotes']=$note->where("ansid=$ansid")->relation('userinfo')->select();
			}
			array_push($arr2,$v['vote']);
		}
// 		array_multisort($arr2,SORT_DESC,$arr);
// 		usort($arr, function($a, $b) {
// 			$al = $a['vote'];
// 			$bl = $b['vote'];
// 			if ($al == $bl)
// 				return 0;
// 			return ($al > $bl) ? -1 : 1;
// 		});
		return $arr;	
	}
	
	/**
	 * 作者		dyc
	 * 函数的描述    根据$uid查询本人的所有回复
	 * 参数列表	$uid=>用户ID
	 * 返回值		Array
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	function queryAnsByUser($uid,$showpage=1,$perPage = 5){
		$m=D('User');
		$ask=D('Ask');
		$arr=array();
		$rs=$m->relation('Answer')->where("id=$uid")->select();
		if(count($rs)>0){
			foreach ($rs[0]['Answer'] as $k => &$v) {
				$v['vote']=$v['zan']-$v['cai'];
				$rs1=$ask->where("id=$v[askid]")->field('title')->select();
				$v['asktitle']=$rs1[0]['title'];
				array_push($arr,$v['vote']);
			}
		};
		array_multisort($arr,SORT_DESC,$rs[0]['Answer']);
		return $rs;
	}
	
	/**
	 * 作者		dyc
	 * 函数的描述    根据所传条件数组修改回复
	 * 参数列表	$arr=>$uid,id(回复ID),修改的内容
	 * 返回值		使用save方法，返回被影响的条数
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function updateAnswer($arr){
		$m=D('Answer');
		$uid=$arr['uid'];
		$id=$arr['id'];
		$m->content=$arr['content'];
		$m->where("uid=$uid and id=$id")->save();
		return $m->where("uid=$uid and id=$id")->select();
	}

	/**
	 * 作者		dyc
	 * 函数的描述   修改answer的赞,踩
	 * 参数列表	$aid  问题id
	 * 			$opt  操作(zan,cai)
	 *    		$uid  用户id
	 *      	$ansid 回答id
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AnswerController
	 * */
	public function updateUpDown($aid,$opt,$uid,$ansid){
		$ans=D('Answer');
		$rs1=$this->insertAnsOpt(array('askid'=>$aid,'uid'=>$uid,'ansid'=>$ansid));
		if($opt=='zan'){
			if($rs1){
				$ans->where("id=$ansid")->setInc('zan',1);// 用户的zan加1
				return $ans->where("id=$ansid")->field('zan,cai')->select();	
			}else{
				return false;
			}
		}else if($opt=='cai'){
			if($rs1){
				$ans->where("id=$ansid")->setInc('cai',1);// 用户的cai加1
				return $ans->where("id=$ansid")->field('zan,cai')->select();
			}else{
				return false;
			}
			 
		}
			
	}
	/**
	 * 作者		戢炳忠
	 * 函数的描述  插入一个操作
	 * 参数列表
	 * 返回值	使用ADD方法，成功后返回此问题的ID
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	
	public function insertAnsOpt($arr){
		$ans=D('AnsOpt');
		if($ans->create($arr)){
			return $ans->add();
		}
	}
	/**
	 * 作者		dyc
	 * 函数的描述   根据所传$Answerid修改answer的赞
	 * 参数列表	$Answerid(回复ID)
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AnswerController
	 * */
	public function updateZanAnswer($Answerid){
		$m=D('Answer');
		$rs=$this->query("select askid from t_answer where id=$Answerid");
		$uid=session('uid');
		$askid=$rs[0]['askid'];
		$time=getTime();
		$s=$this->execute("insert into t_ans_opt(uid,askid,ansid,votetime) values($uid,4,25,'$time')");
		if($s){
			$r=$m->where("id=$Answerid")->setInc('zan');
			return $m->where("id=$Answerid")->select();
		}else{
			return $this->getError();
		}
	}
	
	/**
	 * 作者		dyc
	 * 函数的描述   根据所传$Answerid修改answer的踩
	 * 参数列表	$Answerid(回复ID)
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AnswerController
	 * */
	
	public function updateCaiAnswer($Answerid){
		$m=D('Answer');
		$rs=$this->query("select askid from t_answer where id=$Answerid");
		$uid=session('uid');
		$askid=$rs[0]['askid'];
		$time=getTime();
		$s=$this->execute("insert into t_ans_opt(uid,askid,ansid,votetime) values($uid,4,25,'$time')");
		if($s){
			$r=$m->where("id=$Answerid")->setInc('can');
			return $m->where("id=$Answerid")->select();
		}else{
			return $this->getError();
		}
	
	}

	/**
	 * 作者		戢炳忠
	 * 函数的描述  插入一个问题回复
	 * 参数列表
	 * 	$arr=>array(	
	 *			content    '回复内容',
	 *			'askid'	回复问题ID
	 * 	)
	 * 返回值	使用ADD方法，成功后返回此问题的ID
	 * 函数被访问的接口列表
	 * 	AskController
	 * */
		
	// public function insertAnswer($arr){
	// 	$ask=D('Answer');
	// 	if($ask->create($arr)){
	// 		$ask->add();
	// 	}else {
	// 		$ask->getError();
	// 	}
	// }
	
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述  根据问题ID查询问题回复,通过投票排序高->低
	 * 参数列表
	 * 	$askid
	 * 返回值	使用ADD方法，成功后返回此问题的ID
	 * 函数被访问的接口列表
	 * 	AskController
	 * */
	
	public function queryAnswerByVotes($askid,$showpage,$pagesize){
		$a=D('Answer');
		$u=D('Userinfo');
		$rs=$a->relation(true)->where("askid=$askid")->order('(zan-cai) desc')->page($showpage,$pagesize)->select();
		foreach ($rs as $key=>$val){
			foreach ($val[ansnotes] as $k1=>$v){
					$user=$u->field('id,name,reputation')->find($v[uid]);
					$rs[$key][ansnotes][$k1][user]=$user;
			}
		}
		return $rs;
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述  根据问题ID查询问题回复,通过发表时间排序近->远
	 * 参数列表
	 * 	$askid
	 * 返回值	使用ADD方法，成功后返回此问题的ID
	 * 函数被访问的接口列表
	 * 	AskController
	 * */
	
	public function queryAnswerByTime($askid){
		$a=D('Answer');
		$u=D('Userinfo');
		$rs=$a->relation(true)->where("askid=$askid")->order('publishTime desc')->select();
		foreach ($rs as $key=>$val){
			foreach ($val[ansnotes] as $k1=>$v){
				$user=$u->field('id,name,reputation')->find($v[uid]);
				$rs[$key][ansnotes][$k1][user]=$user;
			}
		}
		return $rs;
	}
}

?>