<?php 
define('VIEW_PATH', ROOT.'view/offline/');
class OfflineController{
	
	function __construct(){
	}


	
	function offline(){
		if(config('offline')['offline']){
			return view::load('offline');
		}
		else{
			return "管理员未开启离线上传功能";
		}
	}
   
}
