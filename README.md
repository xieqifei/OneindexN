# 一：简介

本项目是Oneindex的继承版本。主要针对后台和nexmoe主题进行优化修改。

项目地址：https://github.com/xieqifei/OneindexN

Demo：https://pan.sci.ci

如果安装出现问题，可以将你原来oneindex网站的`/config`目录下的`base.php`和`token.php`拷贝到新网站的`/config`中，我自己在heroku安装时，遇到重复进入安装程序的问题。在vps中，也会重复进入安装程序，但在一段时间后就会正常。

## 修改功能：

- 、选择安装世纪互联/国际版（如需修改版本，需要删除config文件夹里的文件后重新进入安装程序）
- 前台游客/管理员在线上传（后台可关闭游客上传、指定上传路径）
- 前台游客/管理员离线上传（需安装aria2+rclone，后台可关闭游客上传，上传路径在rclone中设置）
- 后台可指定文件夹/全部文件夹，关闭Readme.md、index.html、head.md渲染（如果离线上传路径和在线上传路径不同，可在这里设置离线上传路径为不渲染）
- 外部视频播放器播放。

> 默认在线上传

# 二：部署网站

网站环境尽量使用nginx。后面会用nginx做反代，可以避免修改yaaw的rpc参数。

## 功能：

不占用服务器空间，不走服务器流量，  

直接列出 OneDrive 目录，文件直链下载。  

## 使用及免责协议

[使用及免责协议](./使用及免责协议.md)

## 安装运行

### 源码安装运行：

#### 需求：

1、PHP空间，PHP 5.6+ 需打开curl支持  
2、OneDrive 账号 (个人、企业版或教育版/工作或学校帐户)  
3、OneIndex 程序   

## 配置：

<img width="658" alt="image" src="https://raw.githubusercontent.com/donwa/oneindex/files/images/install.gif">  

### 计划任务  

[可选]**推荐配置**，非必需。后台定时刷新缓存，可增加前台访问的速度。  

```
# 每小时刷新一次token
0 * * * * /具体路径/php /程序具体路径/one.php token:refresh

# 每十分钟后台刷新一遍缓存
*/10 * * * * /具体路径/php /程序具体路径/one.php cache:refresh
```

### Docker 安装运行

- 请参考[TimeBye/oneindex](https://github.com/TimeBye/oneindex)

## 特殊文件实现功能  

` README.md `、`HEAD.md` 、 `.password`特殊文件使用  

可以参考[https://github.com/donwa/oneindex/tree/files](https://github.com/donwa/oneindex/tree/files)  

**在文件夹底部添加说明:**  

>在 OneDrive 的文件夹中添加` README.md `文件，使用 Markdown 语法。  

**在文件夹头部添加说明:**  

>在 OneDrive 的文件夹中添加`HEAD.md` 文件，使用 Markdown 语法。  

**加密文件夹:**  

>在 OneDrive 的文件夹中添加`.password`文件，填入密码，密码不能为空。  

**直接输出网页:**  

>在 OneDrive 的文件夹中添加`index.html` 文件，程序会直接输出网页而不列目录。  
>配合 文件展示设置-直接输出 效果更佳。  

## 命令行功能  

仅能在PHP CLI模式下运行  

**清除缓存:**  

```
php one.php cache:clear
```

**刷新缓存:**  

```
php one.php cache:refresh
```

**刷新令牌:**  

```
php one.php token:refresh
```

**上传文件:**  

```
php one.php upload:file 本地文件 [OneDrive文件]
```


**上传文件夹:**  

```
php one.php upload:folder 本地文件夹 [OneDrive文件夹]
```

例如：  

```
//上传demo.zip 到OneDrive 根目录  
php one.php upload:file demo.zip  

//上传demo.zip 到OneDrive /test/目录  
php one.php upload:file demo.zip /test/  

//上传demo.zip 到OneDrive /test/目录并将其命名为 d.zip  
php one.php upload:file demo.zip /test/d.zip  

//上传up/ 到OneDrive /test/ 目录  
php one.php upload:file up/ /test/
```

# 三：连接Aria2

Nginx添加反向代理

```
location /jsonrpc {
        proxy_pass http://localhost:6800/jsonrpc;
        proxy_redirect off;
        proxy_set_header        X-Real-IP       $remote_addr;
        proxy_set_header        X-Forwarded-For
        $proxy_add_x_forwarded_for;
        proxy_set_header Host $host;
	}
```

这样RPC可以配置为`http://yoursite/jsonrpc`。网站后台默认的RPC就是`http://yoursite/jsonrpc`

![](https://i.loli.net/2020/06/25/9cY2PiBr6usqXen.png)

**关闭aria2远程RPC**

仅本地也就是443转发的请求能到达6800。

```shell
vim /root/.aria2c/aria2.conf
```

修改aria2配置内容

```
# 启用RPC, 默认:false
enable-rpc=true
# 接受所有远程请求, 默认:false
rpc-allow-origin-all=false
# 允许外部访问, 默认:false
rpc-listen-all=false
# RPC监听端口, 端口被占用时可以修改, 默认:6800
rpc-listen-port=6800
```

> 如果不配置nginx，需要自己修改RPC设置。并且aria2配置需要开放远程RPC

# 五：参考资料

《[github YAAW项目](https://github.com/binux/yaaw)》

《[Issue：一旦使用HTTPS协议就无法连接](https://github.com/mayswind/AriaNg/issues/62)》

《[Nginx 反向代理 Aria2 JSONRPC](https://kenvix.com/post/nginx-proxy-aria2/)》