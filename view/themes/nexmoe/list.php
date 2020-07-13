<?php view::layout('layout')?>
<?php 
function file_ico($item){
  $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
  if(in_array($ext,['bmp','jpg','jpeg','png','gif'])){
  	return "image";
  }
  if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf'])){
  	return "ondemand_video";
  }
  if(in_array($ext,['ogg','mp3','wav'])){
  	return "audiotrack";
  }
  return "insert_drive_file";
}
?>

<?php view::begin('content');?>

<?php if(is_login()):?>
<div class="mdui-container-fluid" >
	<div class="nexmoe-item">
	<button class="mdui-btn mdui-ripple" id="pagesearch">过滤</button>
	<button class="mdui-btn mdui-ripple" id="newfolder">新建文件夹</button>
	<button class="mdui-btn mdui-ripple" id="file_upload">上传文件</button>
	<button class="mdui-btn mdui-ripple multiopt" id="deleteall" style="display: none;">删除</button>
	<button class="mdui-btn mdui-ripple multiopt" id="sharebtn" style="display: none;">分享</button>
	<button class="mdui-btn mdui-ripple singleopt" id="rename" style="display: none;">重命名</button>
	</div>
</div>
<?endif;?> 


<div class="mdui-container-fluid">
<?php if($head):?>
<div class="mdui-typo" style="padding: 20px;">
	<?php e($head);?>
</div>
<?php endif;?>
<style>
.thumb .th{
	display: none;
}
.thumb .mdui-text-right{
	display: none;
}
.thumb .mdui-list-item a ,.thumb .mdui-list-item {
	width:217px;
	height: 230px;
	float: left;
	margin: 10px 10px !important;
}

.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7{
	width:100% !important;
	height:230px;
}

.thumb .mdui-list-item .mdui-icon{
	font-size:100px;
	display: block;
	margin-top: 40px;
	color: #7ab5ef;
}
.thumb .mdui-list-item span{
	float: left;
	display: block;
	text-align: center;
	width:100%;
	position: absolute;
    top: 180px;
}
</style>
<div class="nexmoe-item">
<div class="mdui-row">
	<ul class="mdui-list">
		<li class="mdui-list-item th">
		<?php if(is_login()):?>
			<label class="mdui-checkbox"><input type="checkbox" value="" id="checkall" onclick="checkall()"><i
					class="mdui-checkbox-icon"></i></label>
			<?endif;?> 
		  <div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="downward">expand_more</i></div>
		</li>
		<?php if($path != '/'):?>
		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path.'../');?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7">
				<i class="mdui-icon material-icons">arrow_upward</i>
		    	..
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"></div>
			  <div class="mdui-col-sm-2 mdui-text-right"></div>
		  	</a>
		</li>
		<?php endif;?>
		
		<?php foreach((array)$items as $item):?>
			<?php if(!empty($item['folder'])):?>

		<li class="mdui-list-item mdui-ripple filter" data-sort 
					data-sort-name="<?php echo $item['name'] ;?>"
                    data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
					data-sort-size="<?php echo $item['size'];?>" 
					id="<?php echo $item["id"] ?>">
			<?php if(is_login()):?>
			<label class="mdui-checkbox">
				<input type="checkbox" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
				<i class="mdui-checkbox-icon"></i></label>
			<?endif;?> 		
			<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons">folder_open</i>
		    	<span><?php echo $item['name'];?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php else:?>
		<li class="mdui-list-item file mdui-ripple filter" data-sort
                    data-sort-name="<?php echo $item['name'];?>"
                    data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
					data-sort-size="<?php echo $item['size'];?>" 
					id="<?php echo $item["id"] ?>">
					<?php if(is_login()):?>
			<label class="mdui-checkbox">
				<input type="checkbox" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
				<i class="mdui-checkbox-icon"></i></label>
			<?endif;?> 	
			<a href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
		    	<span><?php e($item['name']);?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php endif;?>
		<?php endforeach;?>
	</ul>
</div>
</div>
<?php if($readme):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; ">
	<div class="mdui-chip">
	  <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
	  <span class="mdui-chip-title">README.md</span>
	</div>
	<?php e($readme);?>
</div>
<?php endif;?>
</div>
<div class="mdui-fab-wrapper" id="myFab">
    <button class="mdui-fab mdui-ripple mdui-color-theme-accent">
      <i class="mdui-icon material-icons">add</i>
      <i class="mdui-icon mdui-fab-opened material-icons">mode_edit</i>
    </button>
    <div class="mdui-fab-dial">
	  
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-pink" onclick="location.href='/?/offline'" style="display: <?php if(!$manager['offline']) echo "none" ;else echo "inline" ?>;"><i class="mdui-icon material-icons">cloud_upload</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red" id="search" style="display: <?php if(!is_login()) echo "none" ;else echo "inline" ?>;"><i class="mdui-icon material-icons">&#xe8b6;</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-orange" onclick="location.href='/?/admin'"><i class="mdui-icon material-icons">account_circle</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-blue" onclick="thumb()"><i class="mdui-icon material-icons" id="format_list">format_list_bulleted</i>
      </button>
    </div>
</div>

<div class="mdui-container">
  <div class="mdui-dialog" id="fileupload-dialog">
    <div class="mdui-dialog-title">文件上传</div>
    <div class="mdui-dialog-content">
		<form id="filesubmit" action="?/onlinefileupload" method="post" enctype="multipart/form-data" >
			<input class="mdui-center" type="file" style="margin: 50px 0;" name="onlinefile" />
			<input type="text" style="display: none;" name="uploadurl" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
		</form>
		<div class="mdui-row-xs-3">
			<div class="mdui-col"></div>
				<div class="mdui-col">
					<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" onclick="submitForm()">上传</button>
				</div>
		</div>
	</div>
    <div class="mdui-dialog-actions">
      <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
    </div>
  </div>
</div>

<div class="mdui-container">
 <div class="mdui-dialog" id="search_form">
    <div class="mdui-dialog-content">
		<form action="?/search" method="post">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">输入关键词</label>
				<input class="mdui-textfield-input" type="text" style="margin: 20px 0;" name="keyword" />
				<div class="mdui-row-xs-3">
				<div class="mdui-col"></div>
					<div class="mdui-col">
						<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple">提交</button>
					</div>
				</div>
			</div>
		</form>
	</div>
  </div>
</div>

<div class="mdui-container">
 <div class="mdui-dialog" id="share">
    <div class="mdui-dialog-content">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">选中的项目链接</label>
				<textarea class="mdui-textfield-input" style="margin: 20px 0;" rows="5" readonly id="sharelinks"></textarea>
			</div>
	</div>
  </div>
</div>
<!-- <script src="https://cdn.jsdelivr.net/gh/xieqifei/OneindexN@v1.31/statics/js/nexmoe.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<script src="statics\themes\nexmoe\js\nexmoe.js"></script>

<?php view::end('content');?>
