<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AnsOptModel extends RelationModel{
	protected $_auto=array(
		array('votetime','getTime',1,'function')
	);
	
}

?>