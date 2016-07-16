<?php 
  namespace Home\Logic;
  use Think\Model;
  class TagLogic extends Model{

  	/**
	  * 作者		dyc
	  * 函数的描述  查询所有标签
	  * 参数列表
	  * 返回值	array
	  * 函数被访问的接口列表
	  * 	TagController
	  *
	  * */
	function queryAllTag(){
		$m=D('Tag');
		return $m->select();
	}
  	/**
  		 * 浣滆�:榛勮悕
  		 * 鍑芥暟鐨勬弿杩�:鎻掑叆涓�釜鏍囩
  		 * 鍙傛暟鍒楄〃:
  		 *     $arr => array(	
		  *			tagname   '鏍囩鍚嶅瓧',
		  *			tagdes  '鏍囩鎻忚堪',
		  *			uid  '鐢ㄦ埛ID',
		  * 	)
  		 * 杩斿洖鍊�:浣跨敤ADD鏂规硶锛屾垚鍔熷悗杩斿洖姝ゆ爣绛剧殑ID
  		 * 鍑芥暟琚闂殑鎺ュ彛鍒楄〃 :TagController
  		 * 
  		 * */
  	function insertTag($arr){
  		$tag = D('Tag');
  		if(!$tag->create()){
  		    $tag ->getError();
  		}else{
  			$tag->add($arr);
  		}
  	}
  	/**
  		 * 浣滆� :榛勮悕
  		 * 鍑芥暟鐨勬弿杩帮細鏌ヨ鎵�湁鐨勬爣绛�
  		 * 鍙傛暟鍒楄〃锛�
  		 * 杩斿洖鍊�:鎵�湁鏍囩鐨勯泦鍚�
  		 * 鍑芥暟琚闂殑鎺ュ彛鍒楄〃锛歍agController
  		 * 
  		 * */
  	function queryAllTagByName($page,$pagesize){
  		$tag = D('Tag');
  		$ask = D('Ask');
  		$arr = $tag->relation('asksid')->page($page,$pagesize)->order('tagname')->select();
  	 	foreach($arr as $key=>$val){
  	    	$askid='';
  			$arr[$key]['asknum']=count($val['asksid']);
        if(count($val['asksid'])>0){
    			foreach ($val['asksid'] as $va){
    				$askid.=$va['id'];
    			}
        }else{
            $askid=0;
        }
  			$arr[$key]['askWeekNum']=$ask->where("id in ($askid) and yearweek(now())=yearweek(publishTime)")->count('id') ;
   			$arr[$key]['askTodayNum'] = $ask->where("id in ($askid) and date_format(now(),'%Y%m%d')=date_format(publishTime,'%Y%m%d')")->count('id') ;
  		}
  		return $arr;
  	}
  	
  	function queryAllTagByAskNum($page=1,$pagesize=20){
  		$p=($page-1)*$pagesize;
  		$tag = D('Tag');
  		$ask = D('Ask');
  		$sql = 'select tt.id,tt.tagname,tt.tagdes, count(ta.id) as asknum from t_tag as tt left join t_ask_tag as tat on(tt.id=tat.tagid) 
  				left join t_ask as ta on(tat.askid=ta.id) group by tt.id order by asknum desc,tt.tagname asc limit '.$p.",".$pagesize;
      $arr = $tag->query($sql);
      foreach($arr as $key=>$val){
        $askid='';
        $rs = $tag->relation('asksid')->where("id=$val[id]")->field('id')->find();
        // var_dump($rs);die;
        if(count($rs['asksid'])>0){
          foreach ($rs['asksid'] as $va){
            $askid.=$va[id].',';
          }
          $askid=rtrim($askid,',');
        }else{
          $askid=0;
        }
        $arr[$key]['askWeekNum']=$ask->where("id in ($askid) and yearweek(now())=yearweek(publishTime)")->count('id') ;
        $arr[$key]['askTodayNum'] = $ask->where("id in ($askid) and date_format(now(),'%Y%m%d')=date_format(publishTime,'%Y%m%d')")->count('id') ;
      }
  		return ($arr);
    }

    
    /**
         * 作者:黄萍
  	 		 * 函数的描述：查询一个标签对应的所有问题(同时需要将问题的其他标签展示)
  	 		 * 参数列表：$tagname=>标签名
  	 		 * 返回值:与标签相关的问题信息
  	 		 * 函数被访问的接口列表：TagController
  	 		 * 
  	 		 * */
  	function queryOneTag($tagid){
  		$tag = D('Tag');
		  $ask = D('Ask');
  		$arr = $tag ->relation('ask')->where("id=$tagid")->select();
		//$arr1 = $ask->relation('tag')->select();
  		foreach($arr as $key=>$val){  
  			$key[asknum]=count($val[ask]);	    			      	
  		}
  		return $arr;  
  	}
  	
  	/**
  	 		 * 作者:黄萍
  	 		 * 函数的描述：查询用户所创建的标签
  	 		 * 参数列表：$uid
  	 		 * 返回值: 与标签相关的问题信息
  	 		 * 函数被访问的接口列表：
  	 		 * 
  	 		 * */
  	function queryTagByUser($uid){
  		$tag = D('Tag');
  		$arr = $tag ->relation('ask')->where("uid=$uid")->select();
  		$allAsk = array();
  		foreach($arr as $key=>$val){
  			foreach($val['ask'] as $k=>$v){
  				if($v['uid']==$uid){
  					array_push($allAsk,$v);
  				}
  			}
  			$arr[$key]['askNum']=count($allAsk);
  		}
  		return $arr; 
  	}
  }
?>