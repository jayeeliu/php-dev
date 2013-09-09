# Xdebug

**`需要server支持xdebug。`**

## 一、xdebug 安装和配置
### xdebug
xdebug 官网 <http://xdebug.org/>

xdebug 文档 <http://xdebug.org/doc/all>

根据系统需要下载不同版本 <http://xdebug.org/download.php>


### xdebug安装

略...


### php.ini 配置
客户端只要做载入xdebug的配置即可，server端配置如下：

	[xdebug]
	; 载入xdebug
	zend_extension			= "/path-to-xdebug.so"
	; xdebug remote debugging 相关配置
	xdebug.remote_enable	= On
	xdebug.remote_mode		= req
	xdebug.remote_host		= localhost
	xdebug.remote_port		= 9000
	xdebug.idekey			= 
	xdebug.remote_handler	= dbgp
	xdebug.remote_connect_back	= 0

#### 配置说明
1. zend_extension="/path-to-xdebug.so"，指定xdebug扩展路径   
	**注意**  
	1) 不能使用extension="/path-to-xdebug.so"加载xdebug [extension与zend_extension区别](http://stackoverflow.com/questions/1758014/whats-the-difference-between-extension-and-zend-extension-in-php-ini);  
	2) 必须是绝对路径 
2. xdebug.remote_enable  
	设置是否启用remote debugging功能，默认不开启；
3. xdebug.remote_mode  
  	1) req（默认），表示在满足条件的情况下（详见各客户端中XDEBUG_SESSION设置），每次请求都会初始化会话（连接调试客户端）；  
	2) jit，只有在php执行发生错误（error或exception）时才初始化调试会话；
4. xdebug.remote_host  
	用于设置调试客户端所在的ip或域名，默认为localhost；  
	当多人共用一个server调试时，可使用xdebug.remote_connect_back参数；  
	在adsl+路由器的网络中，如果调试服务器不在局域网内，则会出现服务器无法连接客户端的情况，可通过 [花生壳+路由器端口转发](http://imgotop.net/use-remote-debugging-xdebug-skills) 功能解决；
5. xdebug.remote_port  
	用于设置调试客户端所用的端口，默认为9000，需要与客户端监听的端口相同；
6. xdebug.idekey  
	默认空，在做web流程的开发调试过程中，忽略此值（笔者建议）；
7. xdebug.remote_handler  
	定义debug server与客户端交互协议，新版本中都使用[DBGp协议](http://xdebug.org/docs-dbgp.php DBGp)
8. xdebug.remote_connect_back  
	由于remote_host限制只能连接一个ip或域名，在没有其他辅助工具的情况下，单台调试服务器对多人调试会很麻烦。当remote_connect_back设置为1时，xdebug会依据`$_SERVER['REMOTE_ADDR']`（看到这个我相信有些人就会想到代理设备的问题，这种方式不能解决这个问题）连接客户端，用户可使用iptables控制访问权限。  
	多人调试也可以参考这篇文章设置 [Configuring PhpStorm, XDebug, and DBGp Proxy Settings for Remote Debugging with Multiple Users](http://matthardy.net/blog/configuring-phpstorm-xdebug-dbgp-proxy-settings-remote-debugging-multiple-users/)。  



## 客户端配置和调试

1. [PHPStorm](./phpstorm.md)







