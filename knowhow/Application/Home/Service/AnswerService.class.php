<?php
namespace Home\Service;
use Think\Model;

class AnswerService extends Model{
	/**
	  * 作者		 戢炳忠
	  * 函数的描述  插入一条回复
	  * 参数列表
	  * 	$arr=>array(	
	  *			askid  '问题ID',
	  *			content  '回复内容',
	  *			uid  '用户ID',
	  * 	)
	  * 返回值	使用ADD方法，成功后返回此回复的ID
	  * 函数被访问的接口列表
	  * 	AnswerController
	  * */

	public function createAnswer($arr){
		$m=D('Answer','Logic');
		return $m->insertAnswer($arr);
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述    根据askid查询回复(需要分页)
	 * 参数列表	array( 
	 * 			$askid=>问题ID
	 * 			$showpage=>当前显示的页数
	 * 			$perPage=>一页显示几条记录
	 * 			$order =>排序规则
	 * )
	 * 返回值		当前问题所有回复的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function showAnswer($arr){
		$m=D('Answer','Logic');
		return $m->queryAnswer($arr);
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
	public function showOneAnswer($ansid){
		$m=D('Answer','Logic');
		return $m->queryOneAnswer($ansid);
	}	
	/**
	 * 作者		戢炳忠
	 * 函数的描述    根据$uid查询本人的所有回复
	 * 参数列表	$uid=>用户ID
	 * 返回值		当前问题所有回复的集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function showAnswerByUser($uid,$showpage=1,$perPage = 5){
		$m=D('Answer','Logic');
		return $m->queryAnsByUser($uid);
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
	public function ansOpt($aid,$opt,$uid,$ansid){
		$m=D('Answer','Logic');
		return $m->updateUpDown($aid,$opt,$uid,$ansid);
	}
	/**
	 * 作者		戢炳忠
	 * 函数的描述    根据所传条件数组修改回复
	 * 参数列表	$arr=>$uid,id(回复ID),修改的内容
	 * 返回值		使用save方法，返回被影响的条数
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function modifyAnswer($arr){
		$m=D('Answer','Logic');
		return $m->updateAnswer($arr);
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述   根据所传$Answerid修改answer的赞
	 * 参数列表	$Answerid(回复ID)
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AnswerController
	 * */
	public function zanAnswer($Answerid){
		$m=D('Answer','Logic');
		return $m->updateZanAnswer($Answerid);
		
	}
	/**
	 * 作者		戢炳忠
	 * 函数的描述   根据所传$Answerid修改answer的踩
	 * 参数列表	$Answerid(回复ID)
	 * 返回值		布尔值
	 * 函数被访问的接口列表
	 *		AnswerController
	 * */
	
	public function caiAnswer($Answerid){
		$m=D('Answer','Logic');
		return $m->updateCaiAnswer($Answerid);
		
	}
	
	
}

?>