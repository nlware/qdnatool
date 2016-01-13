<?php
/**
 * App Utility class.
 *
 */
class AppUtil {

/**
 * printIndex method
 *
 * @param int $index Index
 * @return string
 */
	public static function printIndex($index) {
		return chr(ord('A') + $index);
	}

/**
 * printValue method
 *
 * @param int $value Value
 * @return string
 */
	public static function printValue($value) {
		return chr(ord('A') + $value - 1);
	}

}
