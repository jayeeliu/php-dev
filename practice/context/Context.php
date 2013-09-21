<?php
/**
 * 全局的上下文环境，用于保存请求参数和用户信息等
 *
 * context一旦初始化，就不能再修改
 * 部分信息可在调用时初始化，如用户信息，此时类中需要包含get_字段名()的方法来获取并设置信息
 * 注意：即使是可设置的属性，也只能设置一次
 *
 * 使用代码
 * <code>
 *  $c = array(
 *      'a' => 'aa',
 *      'b' => 'bb',
 *  );
 *  $cc = CContext::instance($c);
 *  // 以下方式获取到的信息是相同的
 *  $cc->a   = 1;
 *  $cc['a'] = 1;
 *
 *  // 第一次调用存在get_xxx方法的属性时，offsetGet会调用对应的get_xxx来获取并设置相应项的信息
 *  $cc->userinfo;
 *  // 此类属性暂时可通过外界设置一次，如 $cc->aaa = '1111111';
 *  // 只能设置1次，重复赋值将抛出异常，看实际需求，可能在未来版本中去掉此功能
 * </code>
 *
 * for auto complete
 * @property string type
 * @property array userinfo
 */
class CContext extends Context {
	/**
	 * 取属性：可依情况调用个性化的 get_XXX 函数
	 *
	 * @param string $name 属性名
	 * @return mixed 属性值
	 */
	public function offsetGet($name) {
		$method = 'get_' . $name;

		if (!$this->offsetExists($name) && method_exists($this, $method)) {
			$this->$method();
		}
		return parent::offsetGet($name);
	}

	/**
	 * 设置属性：属性只能设置一次
	 * @param string $name 属性名
	 * @param mixed $value 属性值
	 * @return bool 设置成功返回 true
	 * @throws Exception 设置失败抛出异常（主要是发生重复设置时抛出异常）
	 */
	public function offsetSet($name, $value) {
		if ($this->offsetExists($name)) {
			throw new Exception(__CLASS__.'\'s property can not be overrided');
		}

		parent::offsetSet($name, $value);
		return true;
	}

	/**
	 * 获取用户信息
	 * @return void
	 */
	protected function get_userinfo() {
		$user = new User();
		if (parent::offsetGet('user') === null) {
			$userinfo = $user->get();
		}
		unset($user);
	}

}


/**
 * 全局的上下文环境
 */
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

class Context_Exception extends Exception {}
