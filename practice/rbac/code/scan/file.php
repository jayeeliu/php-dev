<?php
/**
 * Class SScan_File
 * @todo 文件类型限制
 */
class SScan_File {
	private static $ls = array();

	/**
	 * list dirs and files
	 *
	 * @param string $path      路径
	 * @param int $deeplimit    限制递归深度
	 * @param int $deep         深度计数
	 * @return array
	 */
	public static function dir($path, $deeplimit=0, $deep=0) {
		$deep   += 1;
		if ($deeplimit != 0 && $deep > $deeplimit) {
			return array();
		}
		$name	= basename($path);
		$dir	= dir($path);
		$ls		= array();
		$ls[$name] = array();

		while ($item = $dir->read()) {
			$tmp = $path. DIRECTORY_SEPARATOR .$item;
			if (is_file($tmp)) {
				$ls[$name][] = $item;
			} else {
				if ($item != '.' && $item != '..') {
					$ls[$name] = array_merge($ls[$name], self::dir($tmp, $deeplimit, $deep));
				}
			}
		}
		return $deep === 1 ? $ls[$name] : $ls;
	}

	/**
	 * list all files in a dir using separator _
	 * @param $path             遍历目录
	 * @param int $deeplimit    遍历深度限制，从1开始计数
	 * @param int $deep         >= 0
	 * @param string $fullpath  忽略，传递已遍历过的路径信息
	 * @return array 一维数组
	 */
	public static function filelist($path, $deeplimit=0, $deep=0, $fullpath='') {
		if ($deep == 0) {
			self::$ls = array();
			$fullpath = $fullpath ? $fullpath : basename($path);
		}

		$deep   += 1;
		if ($deeplimit != 0 && $deep > $deeplimit) {
			return null;
		}
		$dir	= dir($path);

		while ($item = $dir->read()) {
			$tmp = $path. DIRECTORY_SEPARATOR .$item;
			if (is_file($tmp)) {
				self::$ls[] = $fullpath.'_'.$item;
			} else {
				if ($item != '.' && $item != '..') {
					self::filelist($tmp, $deeplimit, $deep, $fullpath.'_'.$item);
				}
			}
		}
		return self::$ls;
	}
}