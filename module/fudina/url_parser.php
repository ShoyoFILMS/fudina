<?php
	$core->addMethod('getURLParam', function(){
		$url=$_SERVER['REQUEST_URI'];
		if(strpos($url,'?')){
			$v1=parse_url($url);
			$v2=explode("&",$v1["query"]);
			foreach($v2 as $i=>$v3){
				$v4[$i]=explode("=",$v3);
				$url_param[$v4[$i][0]]=$v4[$i][1];
			}
			return $url_param;
		}
	});
?>