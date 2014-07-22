<?php
App::uses('AppHelper', 'View/Helper');
class OutputHelper extends AppHelper {

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

		/*
		$value = round($value, 2);
		$places = 2;
		if(round($value) == $value) $places = 0;
		elseif(round($value, 1) == $value) $places = 1;
		*/
		return $this->Number->format($value, array('before' => '', 'places' => $places, 'thousands' => $thousandSeperator, 'decimals' => $decimalPoint));
	}

/**
 * Returns a formatted boolean string given a boolean.
 *
 * @param boolean $value A boolean
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
 */
	public function html($value) {
		//TODO: use regex to set all link targets to "_blank"
		$value = str_replace(' target="_self"', '', $value);
		$value = str_replace('<a href=', '<a target="_blank" href=', $value);
		return $value;
	}
}
