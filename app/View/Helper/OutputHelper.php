<?php
App::uses('AppHelper', 'View/Helper');
class OutputHelper extends AppHelper {

	public $helpers = array('Number', 'Time');

	public function date($value) {
		return empty($value)?__('n/a'):$this->Time->format(__('Y-m-d'), $value);
	}

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

	public function boolean($value) {
		if ($value === false) {
			return __('No');
		} elseif ($value === true) {
			return __('Yes');
		}
		return __('n/a');
	}

	public function html($value) {
		//TODO: use regex to set all link targets to "_blank"
		$value = str_replace(' target="_self"', '', $value);
		$value = str_replace('<a href=', '<a target="_blank" href=', $value);
		return $value;
	}
}
