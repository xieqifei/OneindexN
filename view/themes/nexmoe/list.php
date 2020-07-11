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

		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons">folder_open</i>
		    	<span><?php e($item['name']);?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php else:?>
		<li class="mdui-list-item file mdui-ripple">
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
<script>
$ = mdui.JQ;
$.fn.extend({
    sortElements: function (comparator, getSortable) {
        getSortable = getSortable || function () { return this; };

        var placements = this.map(function () {
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);
            };
        });

        return [].sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });
    }
});

function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}

function thumb(){
	if($('#format_list').text() == "apps"){
		$('#format_list').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$('#format_list').text("apps");
		$('.nexmoe-item').addClass('thumb');
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image" || $(this).text() == "ondemand_video"){
				var href = $(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$(this).hide();
				$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
			}
		});
	}

}	

mdui.JQ('newfolder').on('click', function () {
		mdui.prompt('新建文件夹名称',
			function (value) {
				var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
				httpRequest.open('POST', '?/createfolder?foldername='.value, true); //第二步：打开连接
				httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
				httpRequest.send();//发送请求 将情头体写在send中
				/**
				* 获取数据后的处理程序
				*/
				httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
					if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功
						var json = httpRequest.responseText;//获取到服务端返回的数据
						console.log(json);
					}
				};
				mdui.alert('新建文件夹' + value );
			},
			function (value) {
			mdui.alert('你输入了：' + value + '，点击了取消按钮');
			}
		);
	});

$(function(){
	$('.file a').each(function(){
		$(this).on('click', function () {
			var form = $('<form target=_blank method=post></form>').attr('action', $(this).attr('href')).get(0);
			$(document.body).append(form);
			form.submit();
			$(form).remove();
			return false;
		});
	});

	$('.icon-sort').on('click', function () {
        let sort_type = $(this).attr("data-sort"), sort_order = $(this).attr("data-order");
        let sort_order_to = (sort_order === "less") ? "more" : "less";

        $('li[data-sort]').sortElements(function (a, b) {
            let data_a = $(a).attr("data-sort-" + sort_type), data_b = $(b).attr("data-sort-" + sort_type);
            let rt = data_a.localeCompare(data_b, undefined, {numeric: true});
            return (sort_order === "more") ? 0-rt : rt;
        });

        $(this).attr("data-order", sort_order_to).text("expand_" + sort_order_to);
    });

});
</script>

<div class="mdui-fab-wrapper" id="myFab">
    <button class="mdui-fab mdui-ripple mdui-color-theme-accent">
      <i class="mdui-icon material-icons">add</i>
      <i class="mdui-icon mdui-fab-opened material-icons">mode_edit</i>
    </button>
    <div class="mdui-fab-dial">
	  <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red" id="newfolder"><i class="mdui-icon material-icons">create_new_folder</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-pink" onclick="location.href='/?/offline'"><i class="mdui-icon material-icons">cloud_upload</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red" id="open"><i class="mdui-icon material-icons">file_upload</i>
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
		<form action="?/onlinefileupload" method="post" enctype="multipart/form-data" style="display: <?php if(!$online) echo "none" ;else echo "inline" ?>;">
			<input class="mdui-center" type="file" style="margin: 50px 0;" name="onlinefile" />
			<input type="text" style="display: none;" name="uploadurl" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
			<div class="mdui-row-xs-3">
			<div class="mdui-col"></div>
				<div class="mdui-col">
					<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple">上传</button>
				</div>
			</div>
		</form>
		<h4 style="display: <?php if($online) echo "none" ;else echo "inline" ?>;">管理员未允许游客上传</h4>
	</div>
    <div class="mdui-dialog-actions">
      <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
    </div>
  </div>

</div>
		
<script>
    var inst = new mdui.Fab('#myFab');

	var inst = new mdui.Dialog('#fileupload-dialog');

	// method
	document.getElementById('open').addEventListener('click', function () {
	inst.open();
	});


	// event
	var dialog = document.getElementById('dialog');

	dialog.addEventListener('open.mdui.dialog', function () {
	console.log('open');
	});

	dialog.addEventListener('opened.mdui.dialog', function () {
	console.log('opened');
	});

	dialog.addEventListener('close.mdui.dialog', function () {
	console.log('close');
	});

	dialog.addEventListener('closed.mdui.dialog', function () {
	console.log('closed');
	});

	dialog.addEventListener('cancel.mdui.dialog', function () {
	console.log('cancel');
	});

	dialog.addEventListener('confirm.mdui.dialog', function () {
	console.log('confirm');
	});


</script>
<?php view::end('content');?>
