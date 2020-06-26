<?php view::layout('layout')?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">

	<div class="mdui-typo">
	  <h1> 离线上传<small>    需安装aria2和rclone</small></h1>
  </div>
  <form action="" method="post">
		<div class="mdui-textfield">
		  <h4>不登陆使用前台离线上传</h4>
		  <label class="mdui-textfield-label"></label>
		  <label class="mdui-switch">
			  <input type="checkbox" name="offline" value="1" <?php echo ($config['offline']==false)?'':'checked';?>/>
			  <i class="mdui-switch-icon"></i>
		  </label>
		</div>
	   <Br>
	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
     </button>
	</form>
</div>
<?php view::end('content');?>