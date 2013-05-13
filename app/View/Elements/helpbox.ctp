<div id="tabHelp">
  <?php 
    if(!empty($url)):
      //echo file_get_contents($url);
    
    $html = file_get_contents($url);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $body = $xpath->query('/html/body');
    echo  $body->item(0);
    
    
    
    endif;
  ?>
</div>