<?php
namespace Home\Service;
use Think\Model;

class AsknoteService extends Model{
		/**
		  * 作者		戢炳忠
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
		
	public function createAsknote($arr){
		
	}
	
	/**
	 * 作者		戢炳忠
	 * 函数的描述	根据问题ID 用户ID查询备注
	 * 参数列表	$arr=>array{
	 * 				uid=>用户ID
	 * 				aid=>问题ID
	 * 				}
	 * 返回值
	 * 函数被访问的接口列表     
	 *
	 * */
	public function showAsknote($arr,$showpage=1,$perPage = 5){
		
	}
	

	
	
}

?>