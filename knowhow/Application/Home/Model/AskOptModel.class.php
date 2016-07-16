<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AskOptModel extends RelationModel{
	protected $_auto=array(
		array('votetime','getTime',1,'function')
	);
	
}

?>