<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AsknoteModel extends RelationModel{
	/**
	 * 作者		戢炳忠
	 * 表间关系 :
	 * 		userinfo=>ask与userinfo(问题对应的人)之间的关系(多对一)
	 * */
	protected $_link=array(
			'userinfo' => array(
					'mapping_type' => self::BELONGS_TO,
					'class_name' => 'Userinfo',
					'foreign_key' => 'uid',
					'mapping_name' => 'userinfo',
			),
	);
	protected $_auto=array(
			array('publishtime','getTime',1,'function')
	);
}

?>