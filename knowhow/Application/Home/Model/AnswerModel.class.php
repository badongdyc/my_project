<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AnswerModel extends RelationModel{
	/**
	 * 作者		戢炳忠
	 * 表间关系 :
	 * 		ansnote	=>answer与ansnote(回复对应的问题)之间的关系(多对多)
	 * 		userinfo=>ask与userinfo(问题对应的人)之间的关系(多对一)
	 * */
	protected $_link=array(
			'ansnote'  =>array(
					'mapping_type'	=> self::HAS_MANY,
					'class_name'	=> 'Ansnote',
					'mapping_name'	=> 'ansnotes',
					'foreign_key'	=> 'ansid',
			),
			'userinfo' => array(
					'mapping_type' => self::BELONGS_TO,
					'class_name' => 'Userinfo',
					'foreign_key' => 'uid',
					'mapping_name' => 'userinfo',
			),
	);
	protected $_auto=array(
			array('publishTime','getTime',1,'function')
	);

}

?>