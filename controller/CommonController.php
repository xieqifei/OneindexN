<?php 
define('VIEW_PATH', ROOT.'view/');
class CommonController{
	
	function __construct(){
	}
	//离线下载
	function offline(){
		if(config('offline')['offline']||is_login()){
			return view::load('common/offline');
		}
		else{
			return view::load('common/tips');
		}
	}

	//搜索
	function search(){
		if(is_login()){
			if($_POST['keyword']){
				$keyword=$_POST['keyword'];
				$items = onedrive::search($keyword);
				$navs=array();
				$searchinfo['keyword']=$keyword;
				$searchinfo['count']=count($items);
				return view::load('common/search')->with('items',$items);
			}else{
				return '参数错误';
			}
		}
		else{
			return '请登陆后尝试';
		}
		
	}
	//新建文件夹
	//post参数：uploadurl，当前url的路径
	function create_folder(){
		if(is_login()){
			$urlinfo=parse_url($_POST['uploadurl']);
			if(stristr($_POST['uploadurl'],'?')){
				$paths = explode('/', rawurldecode($urlinfo['query']));
			}else{
				$paths = explode('/', rawurldecode($urlinfo['path']));
			}
			$paths=array_values($paths);
			$remotepath = get_absolute_path(join('/', $paths));
			$data = onedrive::create_folder(str_replace('//','/',config('onedrive_root').$remotepath),$_POST['foldername']);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $data;
		}
		else{
			return '未登录无法新建文件夹';
		}
	}
	//重命名
	//post参数：name：新名称；itemid：itemid
	function rename(){
		if(is_login()){
			$newname=$_POST['name'];
			$itemid=$_POST['itemid'];
			$resp=onedrive::rename($itemid,$newname);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $resp;
		}
		else{
			return '未登录无法重命名';
		}
	}
	//删除
	//传入一个stringfy后的itemid的数组
	function deleteitems(){
		if(is_login()){
			$data = file_get_contents( "php://input" );
			$items = json_decode( $data );
			$resp=onedrive::delete($items);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $data;
		}
		else{
			return '未登录无法重命名';
		}
	}

	//任意文件在线上传，从个人电脑上传
	//post参数：onlinefile：一个文件；uploadurl：当前url路径
	function onlinefileupload()
	{
		
		if($this->uploadcondition($_FILES["onlinefile"]) ){
			$filename = $_FILES["onlinefile"]['name'];
			$content = file_get_contents( $_FILES["onlinefile"]['tmp_name']);
			//管理员不受上传目录限制
			if(is_login()){
				//获取路径
				$paths = explode('/', rawurldecode($_POST['uploadurl']));
				if(strcmp($paths[1],'?')==0){
					array_shift($paths);
					array_shift($paths);
				}
				//$paths=array_shift($paths);
				$remotepath = get_absolute_path(join('/', $paths));
			}
			//游客只能上传到指定目录
			else{
				$remotepath =  config('offline')['upload_path'];
			}
			$remotefile = $remotepath.$filename;
			$result = onedrive::upload(str_replace('//','/',config('onedrive_root').$remotefile), $content);
			
			if($result){
				$root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).config('root_path');
				$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$url = $_SERVER['HTTP_HOST'].$root.'/'.$remotepath.rawurldecode($filename).((config('root_path') == '?')?'&s':'?s');
				$url = $http_type.str_replace('//','/', $url);
				// view::direct($url);
				return '上传成功';
			}
		}
		return '上传失败';
	}

	function uploadcondition($file){
		
		if($file['size'] > 4485760 || $file['size'] == 0){
			return false;
		}
		if(config('offline')['online']==false&&$_COOKIE['admin'] != md5(config('password').config('refresh_token'))){
			return false;
		}

		return true;
	}
}
