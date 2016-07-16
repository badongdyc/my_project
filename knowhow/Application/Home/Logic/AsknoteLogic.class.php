<?php
namespace Home\Logic;
use Think\Model\RelationModel;

class AsknoteLogic extends RelationModel {
	/**
	 * 作者		dyc
	 * 函数的描述  插入一条问题备注
	 * 参数列表
	 * 	$arr=>array(
	 *			aid   '问题ID',
	 *			content  '问题备注内容',
	 *			publishTime  '备注发布时间',
	 *			uid  '用户ID',
	 * 	)
	 * 返回值	使用ADD方法，成功后返回此备注的ID
	 * 函数被访问的接口列表
	 * 	 AsknoteController
	 * */
	
	public function insertAsknote($arr){
		
	}
}

?>