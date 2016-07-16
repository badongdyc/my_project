<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AskModel extends RelationModel{
	/**
	  * 作者		戢炳忠
	  * 表间关系 :
	  * 		asknote	=>ask与asknote(问题备注)之间的关系(一对多)
	  * 		askinfo	=>ask与askinfo(问题被编辑)之间的关系(一对多)
	  * 		answer	=>ask与answer(问题被回复)之间的关系(一对多)
	  * 		tag		=>ask与tag(问题对应的标签)之间的关系(多对多)
	  * 		userinfo=>ask与userinfo(问题对应的人)之间的关系(多对一)
	  * */
	protected $_link=array(
			'asknote'  =>array(
					'mapping_type'	=>	self::HAS_MANY,
					'class_name'	=>	'Asknote',
					'foreign_key'	=>	'aid',
					'mapping_name'	=>	'asknotes',
					'mapping_limit' => 	'5',
					'mapping_order'	=>	'publishtime asc',
					),
			'askinfo'  =>array(
					'mapping_type'	=>	self::HAS_MANY,
					'class_name'	=>	'Askinfo',
					'foreign_key'	=>	'aid',
					'mapping_name'	=>	'askinfos',
					'mapping_order'	=>	'edittime asc',
				),
			'answer'  =>array(
					'mapping_type'	=>	self::HAS_MANY,
					'class_name'	=>	'Answer',
					'foreign_key'	=>	'askid',
					'mapping_name'	=>	'answers',
				),
			'tag'  =>array(
					'mapping_type'			=> self::MANY_TO_MANY,
					'class_name'			=> 'Tag',
					'mapping_name'			=> 'tags',
					'foreign_key'			=> 'askid',
					'relation_foreign_key'	=> 'tagid',
					'relation_table'		=> 't_ask_tag',
					'mapping_fields'		=> 'id,tagname', 
				),
			'userinfo' => array(
					'mapping_type' 	=> self::BELONGS_TO,
					'class_name' 	=> 'Userinfo',
					'foreign_key' 	=> 'uid',
					'mapping_name' 	=> 'userinfo',
					'mapping_fields'=> 'name,reputation,url',
					'as_fields'=> 'name,reputation,url',
				),
			'username' => array(
					'mapping_type' => self::BELONGS_TO,
					'class_name' => 'Userinfo',
					'foreign_key' => 'uid',
					'mapping_name' => 'username',
					'mapping_fields'=> 'name,reputation',
					// 'as_fields'=> 'name:aa,reputation:bb'
			),
		);
	protected $_auto=array(
			array('publishTime','getTime',1,'callback'),
			array('editTime','getTime',1,'callback')
			);
	function getTime(){
		return date('Y-m-d H:i:s',time());
	}
}

?>