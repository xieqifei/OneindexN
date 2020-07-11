<?php 
define('VIEW_PATH', ROOT.'view/');
class OfflineController{
	
	function __construct(){
	}
	
	function offline(){
		if(config('offline')['offline']||$_COOKIE['admin'] == md5(config('password').config('refresh_token'))){
			return view::load('offline/offline');
		}
		else{
			return view::load('offline/tips');
		}
	}
   
}
