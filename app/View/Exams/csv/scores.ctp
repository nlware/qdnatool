<?php 
$fp = fopen("php://output", 'w');
$headers = array(__('Subject'), __('Total score'));
fputcsv($fp, $headers);
if(!empty($scores)):
  foreach ($scores as $score):
    $fields = array();
    $fields[] = $score['Subject']['value'];
    $fields[] = $score[0]['score_total'];
    fputcsv($fp, $fields);
  endforeach;
endif;
fclose($fp);