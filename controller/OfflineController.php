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
	//搜索
	function search(){
		if($_POST['keyword']){
			$keyword=$_POST['keyword'];
			$items = onedrive::search($keyword);
			$navs=array();
			$searchinfo['keyword']=$keyword;
			$searchinfo['count']=count($items);
			return view::load('offline/tips');
			// return view::load('themes/nexmoe/search')->with('title', '123')
			// ->with('navs', $navs)
			// ->with('items', $items);
		}else{
			return '参数错误';
		}
	}

}
