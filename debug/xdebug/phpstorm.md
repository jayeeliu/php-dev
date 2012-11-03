# PHPStorm & Xdebug



**`在做下面的操作之前请先确保您已经配置过debug server端的Xdebug`**，如果没有请先配置[xdebug](./README.md)


[PHPStorm提供多种调试方式](http://www.jetbrains.com/phpstorm/webhelp/debugging-php-applications.html)，如下面列出的方式（菜单中的Run -> Edit Configurations可以找到所有方式），这里主要关注基于xdebug的PHP Remote Debug。
 
1. PHP Web Application
2. PHP HTTP Request
3. PHP Remote Debug

PHP Remote Debug Server是指启用了xdebug并用于运行需要调试程序的服务器端，他与PHPStorm通过[DBGp][DBGp]协议交互，交互流程如下：

1. 配置并启动PHP Debug Connections![phpstrom-debug-connections](../../images/debug/xdebug/phpstrom-debug-connections-start.png?raw=true)，启动后，PHPStorm开始监听端口
2. 用户手动或server端自动触发server端进入调试状态
3. Debug server通过[DBGp][DBGp]协议连接PHPStorm，默认情况下，Debug server会根据xdebug.remote_server和xdebug.remote_port连接PHPStorm。


## 配置PHPStorm

### 确认 Settings - PHP - Debug 配置
在PHPStorm的 Settings - PHP - Debug 菜单下确认xdebug配置
![phpstorm-settings-php-debug-xdebug](../../images/debug/xdebug/phpstorm-settings-php-debug-xdebug.png?raw=true)

Debug port默认是9000，与debug server端的xdebug.remote_port对应。


### 设置remote debug server

- [官方帮助文档](http://www.jetbrains.com/phpstorm/webhelp/configuring-xdebug.html)

- 菜单中选择 Run -> Edit Configurations… ，在对话框中使用“+”添加“PHP Remote Debug”

- 设置“Name”

- 设置“Configuration”

  1. Servers，用来设置运行程序的server信息，通过右侧的按钮设置server，如下图：
	![phpstorm-add-remote-debug-server](../../images/debug/xdebug/phpstorm-add-remote-debug-server.png?raw=true)
	1)  Host指debug server，如果是本地可以直接设置localhost，事例中设置域名，并在hosts文件中添加域名指向，便于使用不同server调试；  
	2)  当server端的代码路径和本地代码路径不同时，就需要用“Use Path Mappings”来设置本地和server端文件路径的对应关系，注意是绝对路径，不能是相对路径或软连接；
	![phpstorm-use-path-mappings](../../images/debug/xdebug/phpstorm-use-path-mappings.png?raw=true)
	
  2. ide key(session id)，此值设置与下面介绍的XDEBUG_SESSION设置相同即可；

- 确认后，启动 Run -> Start Listen PHP Debug Connetctions，此时PHPStorm开始监听Debug port。



## 触发Remote Debug Server进入调试状态

Debug Server在配置xdebug后并不会进入调试状态，用户可以通过向debug server的请求中添加 XDEBUG_SESSION 来触发，XDEBUG_SESSION可用来标识当前请求需要与PHPStorm中的哪个项目相关（上一节中的ide key对应），触发方式如下：

1. 通过url参数方式  
	将 XDEBUG_SESSION 作为请求参数，值可自定义，如http://x.com/?XDEBUG_SESSION=PHPSTORM；
2. 通过cookie（建议使用此方式）  
	将 XDEBUG_SESSION 作为cookie传递（进程cookie）  
	PHPStorm官方提供了生成用一小段js作为书签的方式种cookie，[PHPStorm生成书签](http://www.jetbrains.com/phpstorm/marklets/)，还提供了一些浏览器提供了插件：  
	1) [firefox](https://addons.mozilla.org/en-US/firefox/addon/58688/)  
	2) [chrome](https://chrome.google.com/extensions/detail/eadndfjplgieldjbigjakmdgkmoaaaoc)  
	3) [safari](https://github.com/benmatselby/xdebug-toggler)  
	4) [opera](https://addons.opera.com/addons/extensions/details/xdebug-launcher/?display=en)  



## Debugging
如果您已经配置好Remote Debug Server和PHPStorm，就可以在代码中加入断点了（直接点击代码左侧的IDE边框即可）。在代码中在使用 XDEBUG_SESSION 触发debug server进入调试状态时，PHPStorm应该会自动调到您窗口的最前端，那开始Debugging吧。





[DBGp]: http://xdebug.org/docs-dbgp.php DBGp

