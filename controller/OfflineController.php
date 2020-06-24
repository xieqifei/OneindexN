<?php 
define('VIEW_PATH', ROOT.'view/offline/');
class OfflineController{
	
	function __construct(){
	}


	
	function offline(){
		
		return view::load('offline');
	}
   
}
