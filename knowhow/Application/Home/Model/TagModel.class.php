<?php
namespace Home\Model;
use Think\Model\RelationModel;

class TagModel extends RelationModel{
	
		/**
		 * 作者		戢炳忠
		 * 表间关系 :
		 * 		ask	=>tag与ask(标签对应的问题)之间的关系(多对多)
		 * */
		protected $_link=array(
			'ask'  =>array(
				'mapping_type'			=> self::MANY_TO_MANY,
				'class_name'			=> 'Ask',
				'mapping_name'			=> 'asks',
				'foreign_key'			=> 'tagid',
				'relation_foreign_key'	=> 'askid',
				'relation_table'		=> 't_ask_tag'
			),
			'asksid' =>array(
                'mapping_type'			=> self::MANY_TO_MANY,
				'class_name'			=> 'Ask',
				'mapping_name'			=> 'asksid',
				'foreign_key'			=> 'tagid',
				'relation_foreign_key'	=> 'askid',
				'relation_table'		=> 't_ask_tag',
				'mapping_fields'		=>'id'
			)		
		);
		
		protected $_auto=array(
			array('createTime','getTime',1,'function')
		);

}

?>