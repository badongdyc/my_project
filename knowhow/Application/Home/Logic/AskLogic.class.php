<?php
namespace Home\Logic;

use Think\Model;

class AskLogic extends Model{
	/**
	  * 作者		戢炳忠
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
	
	public function insertAsk($arr){
		extract($arr);
		extract($tag);
		$ask=D('Ask');
		$tagm=M('ask_tag');
		if($ask->create($arr)){
			$askid=$ask->add();
			$tagarr=array('askid'=>$askid,'tagid'=>$tagid);
			$rs=$tagm->add($tagarr);
			if($rs&&$askid){
				return $askid;	
			}else{
				return $ask->getError();
			}
			// return $ask->add();
		}else {
			return $ask->getError();
		}
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述  修改问题
	 * 参数列表
	 * 	$arr=>array(
	 * 	)
	 * 返回值	使用save方法，成功后返回修改了的条数
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	
	public function updateAsk($arr){
		$ask=D('Ask');
		if($ask->create($arr)){
			return $ask->save();
		}else {
			return $ask->getError();
		}
	}
	
	/**
	 * 作者		dyc
	 * 函数的描述  修改问题的浏览量
	 * 参数列表
	 *        $askid 问题id
	 * 返回值	成功后返回修改了的条数
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	
	public function updateAskView($askid){
		$ask=D('Ask');
		$rs=$ask->where("id=$askid")->setInc('viewCount',1);
		if($rs){
			return  $ask->where("id=$askid")->field('viewCount')->select();
		}else{
			return false;
		}
	}
	/**
	 * 作者		戢炳忠
	 * 函数的描述  根据所传$Askid修改ask的赞
	 * 参数列表	$Askid(回复ID)
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function updateUpDown($aid,$opt,$uid){
		$ask=D('Ask');
		$rs1=$this->insertAskOpt(array('askid'=>$aid,'uid'=>$uid));
		if($opt=='zan'){
			if($rs1){
				$ask->where("id=$aid")->setInc('zan',1);// 用户的zan加1
				return $ask->where("id=$aid")->field('zan,cai')->select();	
			}else{
				return false;
			}

		}else if($opt=='cai'){
			if($rs1){
				$ask->where("id=$aid")->setInc('cai',1);// 用户的cai加1
				return $ask->where("id=$aid")->field('zan,cai')->select();
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
	
	public function insertAskOpt($arr){
		$ask=D('AskOpt');
		if($ask->create($arr)){
			return $ask->add();
		}
	}
	/**
	 * 作者		戢炳忠
	 * 函数的描述  插入一个再编辑
	 * 参数列表
	 * 	$arr=>array(
	 *			aid   '问题ID',
	 *			aftercontent   '修改后内容',
	 *			edittime   '发布时间',
	 *			uid  '编辑人id',
	 * 	)
	 * 返回值	使用ADD方法，成功后返回此问题的ID
	 * 函数被访问的接口列表
	 * 	AskController
	 *
	 * */
	
	public function insertAskinfo($arr){
		$ask=D('Askinfo');
		if($ask->create($arr)){
			return $ask->add();
		}else {
			return $ask->getError();
		}
	}
	
	private function sort($sql){
		$m=D('Ask');
		$rs=$m->query($sql);
		foreach ($rs as $key=>$val){
			$tag=($m->where("id=$val[id]")->relation(array('tags','username','answers'))->find());
			$rs[$key]['ansnum']=count($tag['answers']);
			$rs[$key]['userinfo']=$tag['username'];
			$rs[$key]['tag']=$tag['tags'];
		}
		return $rs;
	}


	/**
	 * 作者		  戢炳忠
	 * 函数的描述         根据回复、编辑、发布的最新时间查询所有首页展示问题
	 * 参数列表	null
	 * 参数列表	
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表     
	 *		AskController
	 * */
	public function queryTopAskByTime(){
		$sql="select id,title,content,zan,cai,viewCount,asktype,bounty,uid, acceptid,editTime,
		publishTime,answerTime,if(editTime>=answerTime,editTime,answerTime) as time from t_ask 
		group by id order by time desc limit 100";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  最近3天被查看数、被回答、被投票多少 最近查询所有首页展示问题
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryTopAskByHot(){
		$sql="select ts.id,ts.title,ts.content,ts.zan,ts.cai,ts.viewCount,ts.asktype,ts.bounty,ts.uid, ts.acceptid,
		ts.editTime,ts.publishTime,ts.answerTime,if(ts.editTime>=ts.answerTime,ts.editTime,ts.answerTime) as time, 
		(select count(ta.id) from t_answer as ta where ta.askid=ts.id) as ansnum from t_ask as ts where DATE_SUB(CURDATE(),INTERVAL 3 DAY)<=date(ts.publishTime) group by id order by viewCount desc,
		ansnum desc, (ts.zan-ts.cai) desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  本周被查看数、被回答、被投票多少查询所有首页展示问题
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryTopAskByHotLastWeek(){
		$sql="select ts.id,ts.title,ts.content,ts.zan,ts.cai,ts.viewCount,ts.asktype,ts.bounty,ts.uid, ts.acceptid,ts.editTime,
		ts.publishTime,ts.answerTime,if(ts.editTime>=ts.answerTime,ts.editTime,ts.answerTime) as time,
		(select count(ta.id) from t_answer as ta where ta.askid=ts.id) as ansnum from t_ask as ts where yearweek(ts.publishTime)= yearweek(now()) group by id order by viewCount desc,
		ansnum desc, (ts.zan-ts.cai) desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  本月被查看数、被回答、被投票多少查询所有首页展示问题
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryTopAskByHotLastMonth(){
		$sql="select ts.id,ts.title,ts.content,ts.zan,ts.cai,ts.viewCount,ts.asktype,ts.bounty,ts.uid, ts.acceptid,
		ts.editTime,ts.publishTime,ts.answerTime,if(ts.editTime>=ts.answerTime,ts.editTime,ts.answerTime) as time,
		(select count(ta.id) from t_answer as ta where ta.askid=ts.id) as ansnum from t_ask as ts where DATE_FORMAT(ts.publishTime,'%Y%m')= DATE_FORMAT(CURDATE(),'%Y%m') group by id order by viewCount desc,
		ansnum desc, (ts.zan-ts.cai) desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  总查看数多少查询所有问题展示页面
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryALlAskByFrequent(){
		$p=($page-1)*$pagesize;
		$sql="select id,title,content,zan,cai,viewCount,uid,publishTime,acceptid from t_ask 
		group by id order by viewCount desc,publishTime desc";
		$rs=$this->sort($sql);
		foreach ($rs as $key=>&$v){
			$v['vote'] = $v['zan']-$v['cai'];
			format($v['viewcount']);
			format($v['vote']);
			format($v['ansnum']);
			// array_push($rs,$v['vote']);
		}
		return $rs;
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  总投票数多少查询所有问题展示页面
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryALlAskByVotes(){
		$p=($page-1)*$pagesize;
		$sql="select id,title,content,zan,cai,viewCount,uid,publishTime,acceptid 
		from t_ask group by id order by (zan-cai) desc,publishTime desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		  戢炳忠
	 * 函数的描述  根据回复、编辑、发布的最新时间查询所有问题
	 * 参数列表	
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryAllAskByActive(){
		$p=($page-1)*$pagesize;
		$sql="select id,title,content,zan,cai,viewCount,asktype,bounty,uid, acceptid,editTime,publishTime,answerTime,
			if(ts.editTime>=ts.answerTime,ts.editTime,ts.answerTime) as time from t_ask group by id order 
			by time desc";
		 return ($this->sort($sql));
	}
	
	/**
	 * 作者		  戢炳忠
	 * 函数的描述  根据发布的最新时间查询所有问题
	 * 参数列表
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryAllAskByNewest($id=false){
		$where =$id?"where id=$id":'';
		$p=($page-1)*$pagesize;
		$sql="select id,title,content,zan,cai,viewCount,uid, acceptid,publishTime 
		from t_ask $where group by id order by publishTime desc";
		$rs=$this->sort($sql);
		// var_dump($rs);die();
		foreach ($rs as $key=>&$v){
			$v['vote'] = $v['zan']-$v['cai'];
			format($v['viewcount']);
			format($v['vote']);
			format($v['ansnum']);
			// array_push($rs,$v['vote']);
		}

		// var_dump($rs);
		return $rs;
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  	通过标签总查看数多少查询所有问题展示页面
	 * 参数列表	$tagid=>标签ID
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryALlAskByFrequentAndTag($tagid){
		$sql="select ta.id,ta.title,ta.content,ta.zan,ta.cai,ta.viewCount,ta.uid,ta.publishTime,ta.acceptid 
		from t_ask as ta join t_ask_tag tat on(ta.id=tat.askid) where tat.tagid='$tagid' group by id order 
		by viewCount desc,publishTime desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		 戢炳忠
	 * 函数的描述  通过标签总投票数多少查询所有问题展示页面
	 * 参数列表	null
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryALlAskByVotesAndTag($tagid){
		$sql="select ta.id,ta.title,ta.content,ta.zan,ta.cai,ta.viewCount,ta.uid,ta.publishTime,ta.acceptid 
		from t_ask as ta join t_ask_tag tat on(ta.id=tat.askid) where tat.tagid='$tagid' 
		group by ta.id order by (ta.zan-ta.cai) desc,ta.publishTime desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		  戢炳忠
	 * 函数的描述  通过标签根据回复、编辑、发布的最新时间查询所有问题
	 * 参数列表
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryAllAskByActiveAndTag($tagid){
		$sql="select ta.id,ta.title,ta.content,ta.zan,ta.cai,ta.viewCount,ta.uid,ta.publishTime,ta.acceptid,ta.editTime,
		ta.publishTime,ta.answerTime,if(ta.editTime>=ta.answerTime,ta.editTime,ta.answerTime) as time from t_ask as ta 
		join t_ask_tag tat on(ta.id=tat.askid) where tat.tagid='$tagid' group by ta.id order by time desc";
		return $this->sort($sql);
	}
	
	/**
	 * 作者		  戢炳忠
	 * 函数的描述  通过标签=>根据发布的最新时间查询所有问题
	 * 参数列表
	 * 返回值		所有问题的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryAllAskByNewestAndTag($tagid){
		$sql="select ta.id,ta.title,ta.content,ta.zan,ta.cai,ta.viewCount,ta.uid,ta.publishTime,ta.acceptid 
		from t_ask as ta join t_ask_tag tat on(ta.id=tat.askid) where tat.tagid='$tagid' group by ta.id 
		order by ta.publishTime desc";
		return $this->sort($sql);
	}
	/**********************************************************************************************/
	/**
	 * 作者		  戢炳忠
	 * 函数的描述  根据ASKID获取某个问题的详细信息
	 * 参数列表	$askid=>问题ID
	 * 返回值		问题详细信息的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryOneAskByAskid($askid){
		$a=D('Ask');
		$u=D('Userinfo');
		$rs=$a->relation(array('asknotes','askinfos','tags','userinfo'))->find($askid);
		format($rs['zan']);
		format($rs['cai']);
		foreach ($rs[asknotes] as $k1=>$asknote){
				$asknoteuid=$u->field('id,name,reputation,url')->find($asknote[uid]);
				$rs[asknotes][$k1][user]=$asknoteuid;
			}
		foreach ($rs[askinfos] as $k1=>$askinfo){
				$askinfouid=$u->field('id,name,reputation,url')->find($askinfo[uid]);
				$rs[askinfos][$k1][user]=$askinfouid;
			}
		return $rs;
	}
    
    /**
	 * 作者		    dyc
	 * 函数的描述   根据tagID获取所有问题
	 * 参数列表		$tagid=>标签id
	 * 返回值		array
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryOneTag($tagid){
		$m=M('AskTag');
		$arr=array();
		$askidarr=$m->where("tagid=$tagid")->field('askid')->select();
		if(count($askidarr)>0){
			foreach ($askidarr as $k => $v) {
				array_push($arr,$v['askid']);
			}
			$idstr=join(',',$arr);
			$sql="select id,title,content,zan,cai,viewCount,uid, acceptid,publishTime 
			from t_ask where id in ($idstr) group by id order by publishTime desc";
			$rs=$this->sort($sql);
			// var_dump($rs);die();
			foreach ($rs as $key=>&$v){
				$v['vote'] = $v['zan']-$v['cai'];
				format($v['viewcount']);
				format($v['vote']);
				format($v['ansnum']);
				// array_push($rs,$v['vote']);
			}
			return $rs;
		}else {
			return false;
		}
	}

	/**
	 * 作者		    dyc
	 * 函数的描述   根据用户id获取所有问题
	 * 参数列表		$uid=>用户id
	 * 返回值		array
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function queryAskByUser($uid){
		$sql="select id,title,zan,cai,publishTime 
		from t_ask where(uid=$uid) group by id order by (zan-cai) desc,publishTime desc";
		$rs=$this->sort($sql);
		// var_dump($rs);die();
		foreach ($rs as $key=>&$v){
			$v['vote'] = $v['zan']-$v['cai'];
			format($v['vote']);
			// array_push($rs,$v['vote']);
		}

		// var_dump($rs);
		return $rs;
	}
	
}

?>


