# 一：简介

Yaaw是个开源的aria2的WebUI，通过这个UI可以使用aria2的rpc功能，向aria2发送下载某文件的指令。我将yaaw嵌入到了Oneindex后台，同时在前台也部署了yaaw供游客使用。

项目地址：https://github.com/xieqifei/OneindexN

Demo：https://pan.sci.ci

后台预览：

![image-20200625111751929](C:\Users\97532\AppData\Roaming\Typora\typora-user-images\image-20200625111751929.png)

# 二：部署网站

网站环境尽量使用nginx。后面会用nginx做反代，可以避免修改yaaw的rpc参数。

在github上将网站下载下来。可以直接下载为zip压缩包

![image-20200625112023229](C:\Users\97532\AppData\Roaming\Typora\typora-user-images\image-20200625112023229.png)

将文件解压到网站根目录。

进入网站首页。配置网站。

Onedrive Directory Index

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

# 三：修改Nginx配置

添加

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

这样RPC可以配置为http://yoursite/jsonrpc。网站后台默认的RPC就是http://yoursite/jsonrpc

![image-20200625113038730](C:\Users\97532\AppData\Roaming\Typora\typora-user-images\image-20200625113038730.png)

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