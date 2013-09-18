<?php
class SScan {
	/**
	 * 存储类和方法列表
	 * @var array[]
	 */
	protected static $list = array();

	/**
	 * 按照 rule 遍历目录中的类和方法
	 *
	 * @todo 相同的dir，不同的rule的处理，速度很慢，需要做cache
	 *
	 * @param $dir
	 * @param array $rule
	 * @return array
	 * @throws SException
	 */
	public static function classes($dir, array $rule=array()) {
		if (!is_dir($dir)) {
			throw new SException('Not a directory: '.$dir, -1);
		}

		if (isset(self::$list[$dir])) {
			return self::$list[$dir];
		}
		self::$list[$dir] = array();

		// 目录的文件列表
		$ls		= SScan_File::filelist($dir);

		//$php = shell_exec('/usr/bin/which php');
		if (is_array($ls)) {
			foreach($ls as $v) {
				// 由于速度太慢，放弃语法检查，但有可能一个文件出现错误导致执行失败
				//$output = shell_exec($php.' -l '.CONTROLLER_ROOT.'/'.$v);
				//if (preg_match('/Errors parsing.*$/', '', $output)) {
				//	continue;
				//}

				$tmp = SScan_Reflecation::classes($v, $rule);
				// 过滤非控制器
				if (is_array($tmp)) {
					self::$list[$dir] += $tmp;
				}
			}
		}

		return self::$list[$dir];
	}

}
