<?php
/**
 * App Utility class.
 *
 */
class AppUtil {

/**
 * Returns a human-friendly presentation for a given an answer option index
 *
 * @param int $index Numeric index (between 0 and 7) of an answer option
 * @return string Human-friendly presentation of an answer option
 */
	public static function optionIndex($index) {
		return chr(ord('A') + $index);
	}

/**
 * Returns a human-friendly presentation for a given an answer option value
 *
 * @param int $value Numeric value (between 1 and 8) of an answer option
 * @return string Human-friendly presentation of an answer option
 */
	public static function optionValue($value) {
		return chr(ord('A') + $value - 1);
	}

}
