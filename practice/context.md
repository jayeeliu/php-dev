# PHP中上下文环境信息使用的一种场景和实现

## 简介
在业务逻辑复杂的web开发场景中，按功能的分层可以使代码结构更加清晰，易于理解。而各层之间经常需要传递和共享一些信息，如请求参数、常用的环境变量和一些在请求中“只读”的信息，如果都采用参数的方式传递，可能会导致参数过的问题（如一些操作失败时需要记录日志，而日志中的非全局信息可能需要通过参数传递到方法或类中才能使用）。如果我们使用单件方式实现一个全局的上下文环境类，用于“只读”的方式保存这些“常用信息”，在一些场景下，会很实用。

## 实现原理
PHP中的 [ArrayObject][ArrayObject] 可以让像使用数组一样使用类的实例，用于数据存储的，而 [ArrayObject][ArrayObject] 中的 offsetGet()、offsetSet()、offsetExists() 可以方便的对属性做访问控制，来实现只读和初始化属性。

### 属性的初始化可参考
```
class Context extends ArrayObject {
	private static $_instance     = array();

	/**
	 * ArrayObjec的构造函数为public，不能降低访问权限
	 * 为了维持context的全局性，使用标志位控制实例化
	 * @var bool
	 */
	private static $_instance_flag  = false;

	final public function __construct($params) {
		if (!self::$_instance_flag) {
			throw new Context_Exception('Please use instance to create Context.');
		}
		parent::__construct($params, ArrayObject::ARRAY_AS_PROPS);
	}

	/**
	 * @param array     $params
	 * @return mixed
	 */
	public static function instance(array $params=array()) {
		$type = get_called_class();
		if (!isset(self::$_instance[$type])) {
			self::$_instance_flag   = true;
			self::$_instance[$type] = new $type($params);
			self::$_instance_flag   = false;
		}
		return self::$_instance[$type];
	}

	/**
	 * 获取context类型，默认使用类的名字
	 * @return string
	 */
	public function type() {
		return get_class($this);
	}
}
```

### 属性的设置
```
public function offsetGet($name) {
	$method = 'get_' . $name;

	if (!$this->offsetExists($name) && method_exists($this, $method)) {
		parent::offsetSet($name, $this->$method());
	}
	return parent::offsetGet($name);
}
```
即可通过属性或命名为 get_attrname 的方法来设置属性值

### “只读”实现
```
public function offsetSet($name, $value) {
	if ($this->offsetExists($name)) {
		throw new Exception(__CLASS__.'\'s property can not be overrided');
	}

	parent::offsetSet($name, $value);
	return true;
}
```

## 代码事例
代码实现很简单，算上注释也只有120行 [context.php](./context/context.php)，使用 [CContext][context.php]::instance(array()); 来初始化context；下面是一些使用的示例代码：

```
$c = array(
    'a'	=> 'aaaaa',
);
$cc = CContext::instance($c);
// 获取属性a的值
$cc->a;
// 或使用
$cc['a'];

// 设置属性有两种方式，直接赋值或实现CContext中的 get_attr 方法（用于复杂逻辑）
$cc->attr = 'value'; // $cc['attr'] = 'value';

// 在CContext中加入如下方法
public function get_attr() {
    $attr = new Attrxxx();
    return $attr->do_something();
}

```

## 注
本文提供的代码需要运行在PHP 5.3.0 及更高版本，因为使用了 [get_called_class](http://php.net/manual/en/function.get-called-class.php)。

[ArrayObject]: http://cn2.php.net/manual/en/class.arrayobject.php
[context.php]: ./context/context.php