<?php
namespace Home\Service;
use Think\Model;

class AnsnoteService extends Model{
	/**
	 * 作者		戢炳忠
	 * 函数的描述  插入一条回复备注
	 * 参数列表
	 * 	$arr=>array(
	 *			ansid   '问题ID',
	 *			content  '回复备注内容',
	 *			publishTime  '备注发布时间',
	 *			uid  '用户ID',
	 * 	)
	 * 返回值	使用ADD方法，成功后返回此备注的ID
	 * 函数被访问的接口列表
	 * 	 AnsnoteController
	 * */
	
	public function createAnsnote($arr){
	
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述	根据回复ID查询备注以及回复人信息
	 * 参数列表	$arr=>array{
	 * 				uid=>用户ID
	 * 				aid=>问题ID
	 * 				}
	 * 返回值		备注以及回复人信息集合
	 * 函数被访问的接口列表
	 *		AskController
	 * */
	public function showAnsnote($arr,$showpage=1,$perPage = 3){
	
	}
	
	
}

?>