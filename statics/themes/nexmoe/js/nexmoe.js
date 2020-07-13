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
var inst1 = new mdui.Fab('#myFab');

//文件上传对话框
var inst2 = new mdui.Dialog('#fileupload-dialog');
// method
document.getElementById('file_upload').addEventListener('click', function () {
    inst2.open();
});

var inst3 = new mdui.Dialog('#search_form');
// method
document.getElementById('search').addEventListener('click', function () {
    inst3.open();
});

//当前页关键词搜索
mdui.JQ('#pagesearch').on('click', function () {
    mdui.prompt('输入过滤的关键词或后缀',
        function (value) {
			var dom_items = document.getElementsByClassName('filter');
			for(var i=0;i<dom_items.length;i++){
				if(dom_items[i].getAttribute('data-sort-name').indexOf(value)==-1){
					dom_items[i].style.display = "none";
				}else{
					dom_items[i].style.display = "";
				}
			}
        },
        function (value) {
        },
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//新建文件夹
mdui.JQ('#newfolder').on('click', function () {
    mdui.prompt('输入文件夹名称',
        function (value) {
            var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
            httpRequest.open('POST', '?/create_folder', true); //第二步：打开连接
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query='foldername='+value+'&uploadurl='+window.location.href;
            httpRequest.send(query);//发送请求 将情头体写在send中
            alert('创建成功2秒后\n自动刷新列表！');
            setInterval(function(){location.reload();},3000);
            /**
             * 获取数据后的处理程序
             */
            httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功
                    // console.log(httpRequest.responseText);
                   
                }
            };
        },
        function (value) {
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//重命名
mdui.JQ('#rename').on('click', function () {
    mdui.prompt('输入新名称',
        function (value) {
            var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
            httpRequest.open('POST', '?/rename', true); //第二步：打开连接
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query='name='+value+'&itemid='+check_val[0];
            httpRequest.send(query);//发送请求 将情头体写在send中
            var item_dom=document.getElementById(check_val[0]);
            var a_href = item_dom.getElementsByTagName('a')[0].getAttribute('href');
            a_href.replace(item_dom.getElementsByTagName('span')[0].innerHTML,value);
            //a_href.replace(new RegExp('/(.*)'+item_dom.getElementsByTagName('span')[0].innerHTML+'/'),'$1'+value);
            item_dom.getElementsByTagName('span')[0].innerHTML=value;
            item_dom.getElementsByTagName('a')[0].setAttribute('href',a_href);
            item_dom.setAttribute('data-sort-name',value);
            /**
             * 获取数据后的处理程序
             */
            httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功

                }
            };
        },
        function (value) {
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//删除文件/文件夹
mdui.JQ('#deleteall').on('click', function(){
    mdui.confirm('请确认是否删除选中项目',
        function(){
            var httpRequest = new XMLHttpRequest();
            httpRequest.open('POST', '?/deleteitems', true);
            httpRequest.setRequestHeader("Content-type","application/json");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query=JSON.stringify(check_val);
            httpRequest.send(query);
            for(var i=0;i<check_val.length;i++){
                document.getElementById(check_val[i]).style.display = 'none';
            }
            /**
             * 获取数据后的处理程序
             */
            httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功

                }
            };
        },
        function(){
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});

function onClickHander(){
    checkitems = document.getElementsByName("itemid");
    check_val = [];
    for (k in checkitems) {
        if (checkitems[k].checked) check_val.push(checkitems[k].value);
    }
    var singleopt = document.getElementsByClassName("singleopt");
    var multiopt = document.getElementsByClassName("multiopt");
    //单文件操作
    if(check_val.length==1){
        for(var i=0;i<singleopt.length;i++){
            singleopt[i].style.display = "inline";
        }
    }
    else{
        for(var i=0;i<singleopt.length;i++){
            singleopt[i].style.display = "none";
        }
    }
    //多文件操作
    if(check_val.length>=1){
        for(var i=0;i<multiopt.length;i++){
            multiopt[i].style.display = "inline";
        }
    }else{
        for(var i=0;i<multiopt.length;i++){
            multiopt[i].style.display = "none";
        }
    }
}
function checkall(){
    var checkall = document.getElementById("checkall");
    var itemsbox = document.getElementsByName("itemid");
    if (checkall.checked == false) {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = false;
        }
    } else {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = true;
        }
    }
    onClickHander();
}
function submitForm() {
    var formData = new FormData($("#filesubmit")[0]);  //重点：要用这种方法接收表单的参数
    $.ajax({
        url : "?/onlinefileupload",
        type : 'POST',
        data : formData,
        // 告诉jQuery不要去处理发送的数据
        processData : false,                 
        // 告诉jQuery不要去设置Content-Type请求头
        contentType : false,
        async : false,
        success : function(data) {
            if(data){
                alert(data);
            }
        }
    });
    alert("上传成功，两秒后刷新页面");
    setInterval(function(){location.reload();},3000);
}

