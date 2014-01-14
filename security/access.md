# PHP访问控制

## 目录访问
在不限制php可访问的路径时，php可以访问到所有其有权限读取的文件，可使用open_basedir限制php可访问的路径。

```
php.ini
open_basedir="/htdocs/:/tmp/"

Apache
<VirtualHost *:80>
	PHP_ADMIN_VALUE open_basedir "/htdocs/:/tmp/"
</VirtualHost>

Nginx
server {
	fastcgi_param  PHP_VALUE  "open_basedir=$document_root:/tmp/"; 
}
```

注意：由于开启open_basedir会导致php不再使用stat cache，所以strace可以看到大量的fstat来检查目录和文件是否可访问，会影响性能。详见 [https://bugs.php.net/bug.php?id=52312](https://bugs.php.net/bug.php?id=52312) 。


## document root中目录权限
web sever运行的用户对document root中目录是否有写权限，一般小型应用可能需要对某些目录有些权限，但这些目录一般不需要放入php文件，所以可以考虑不解析这些目录里的php文件，防止上传webshell，其他目录给读和执行权限，文件给读权限即可。

```
Apache vhost
    <Directory "/data/www/htdocs/nophp">
      AddType text/plain .php
    </Directory>
```

