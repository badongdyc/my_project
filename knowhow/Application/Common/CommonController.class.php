<?php
namespace Common;
use Think\Controller;
use Think\Cache\Driver\Memcache;

class CommonController extends Controller {
	protected $rights;
	function __construct(){
   		 parent::__construct();
  		 $id = session("id");
  		 $this->refreshRights($id);
    }
	    
	    
	function checkRights($optionName){
    	return in_array($optionName,S("rights"));
    }
	    
	function getGrade($optionName){
    	$m=M("Rights");
    	$answer=null;
    	$mem=new Memcache();
    	if(!$grde=$mem->get('grade')){
    		$rs=$m->field('action,grade')->select();
    		$mem->set('grade', $rs);
    	}
    	foreach ($grde as $val){
    		if($optionName==$val['action']){
    			$answer=$val['grade'];
    		}
    	}
    	return $answer;
    }
	function refreshRights($id){
    	$m = M();
    	S("rights",null);
    	$arr=array();
    	$this->rights = $m->query("select action from t_rights where grade < (select reputation from t_userinfo where id = '$id')");
		foreach ($this->rights as $val){
			$arr[]=$val['action'];
		}
    	S('rights',$arr);	
    }
}
?>