<?php
App::uses('AppUtil', 'Lib');
App::uses('AppHelper', 'View/Helper');
/**
 * Output Helper.
 */
class OutputHelper extends AppHelper {

/**
 * List of helpers used by this helper.
 *
 * @var array
 */
	public $helpers = array('Number', 'Time');

/**
 * Returns a formatted date string, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param int|string|DateTime $value UNIX timestamp, strtotime() valid string or DateTime object (or a date format string)
 * @return string Formatted date string
 * @see TimeHelper::format()
 */
	public function date($value) {
		return empty($value)?__('n/a'):$this->Time->format(__('Y-m-d'), $value);
	}

/**
 * Formats a number into a formatted number.
 *
 * @param float $value A floating point number
 * @param int $places Places
 * @return string formatted number
 * @see NumberHelper::format()
 */
	public function decimal($value, $places = 1) {
		$decimalPoint = __('%DECIMAL_POINT%');
		$thousandSeperator = __('%THOUSANDS_SEPARATOR%');
		if ($thousandSeperator == '%THOUSANDS_SEPARATOR%') {
			$thousandSeperator = '';
		}

		return $this->Number->format($value, array('before' => '', 'places' => $places, 'thousands' => $thousandSeperator, 'decimals' => $decimalPoint));
	}

/**
 * Returns a formatted boolean string given a boolean.
 *
 * @param bool $value A boolean
 * @return string Formatted boolean
 */
	public function boolean($value) {
		if ($value === false) {
			return __('No');
		} elseif ($value === true) {
			return __('Yes');
		}
		return __('n/a');
	}

/**
 * Returns a formatted HTML string given a HTML string
 *
 * Will set (replace or add) target attribute of all HTML link elements to _blank
 *
 * @param string $value A HTML string
 * @return string
 * @todo use regex to set all link targets to "_blank"
 */
	public function html($value) {
		$value = str_replace(' target="_self"', '', $value);
		$value = str_replace('<a href=', '<a target="_blank" href=', $value);
		return $value;
	}

/**
 * Returns a human-friendly presentation for a given answer option index
 *
 * @param int $index Numeric index (between 0 and 7) of an answer option
 * @return string Human-friendly presentation of an answer option
 */
	public function optionIndex($index) {
		return AppUtil::optionIndex($index);
	}

/**
 * Returns a human-friendly presentation for a given answer option value
 *
 * @param int $value Numeric value (between 1 and 8) of an answer option
 * @return string Human-friendly presentation of an answer option
 */
	public function optionValue($value) {
		return AppUtil::optionValue($value);
	}

}
