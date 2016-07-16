<?php
namespace Home\Controller;
use Think\Controller;

class TestOneController extends Controller {
		public function log(){
			session('id',2);
		}
		
}

?>