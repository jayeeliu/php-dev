# php-fpm

## 几个概念
### fastcgi

```
FastCGI is simple because it is actually CGI with only a few extensions.
```
fastcgi是一种由改进cgi的协议，web应用中关注的是他将web server与应用分离（对比Apache的mod模式）。

fastcgi的工作流程是Leader/Followers模式，先启一个master（监听请求，解析配置文件，初始化执行环境，并根据请求情况管理worker数量），再启动多个worker（处理请求），当请求过来时，master将请求分配给一个worker，继续监听请求。


### php-fpm



## 参考
1. [搞不清FastCgi与PHP-fpm之间是个什么样的关系](http://segmentfault.com/q/1010000000256516)
2. [www.fastcgi.com](http://www.fastcgi.com/drupal/)