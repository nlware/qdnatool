<?php
switch ($format):
	case 'nld':
		$decimalPoint = ',';
		$delimiter = ';';
		break;
	default:
		$decimalPoint = '.';
		$delimiter = ',';
	break;
endswitch;
$fp = fopen("php://output", 'w');
$headers = array(__('Subject'), __('Total score'));
fputcsv($fp, $headers, $delimiter);
if(!empty($scores)):
	foreach ($scores as $score):
		$fields = array();
		$fields[] = $score['Subject']['value'];
		$fields[] = $this->Number->format($score[0]['score_total'], array('before' => '', 'places' => 2, 'thousands' => '', 'decimals' => $decimalPoint));
		fputcsv($fp, $fields, $delimiter);
	endforeach;
endif;
fclose($fp);