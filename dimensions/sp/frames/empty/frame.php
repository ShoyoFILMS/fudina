<?php
	namespace fudina;
?>
<!DOCTYPE html>
<html>
<head>
<title><?php
	if($core->getPageParam("symbol")!="top"){
		echo $core->getPageParam("title")."|";
	}
	echo "fudina";
?></title>
<meta charset="utf-8">
<?php
	if($core->getPageParam("status")===$core->getPageStatusNum("testing")){
		echo '<meta name="robots" content="noindex,nofollow">';
	}
?>
<meta name="description" content="<?php echo $core->getPageParam("description"); ?>">
<meta name="keywords" content="<?php echo $core->getPageParam("keywords"); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="/dimensions/sp/frames/empty/style.css?" type="text/css" media="all" />
<?php
	if($core->getPageParam("lang_list")!='-'){
		echo $core->getPageParam("lang_list");
	}
	if($core->getPageParam("css_list")!='-'){
		echo$core->getPageParam("css_list");
	}
	if($core->getPageParam("js_list_before")!='-'){
		echo $core->getPageParam("js_list_before");
	}
?>
</head>
<body>
<div id="article" class="clearfix">
<?php
	if($core->getPageParam("symbol")!="top"){
		if($core->getPageParam("status")===$core->getPageStatusNum("testing"))
			echo "<p class=\"alert\">※このページはテスト公開中です。</p>";
		include_once("./dimensions/sp/pages/".$core->getPageParam("symbol")."/page.php");
	}else
		include_once("./dimensions/sp/pages/top/page.php");
?>
</div><!--end article-->
<?php
	if($core->getPageParam("js_list_after")!='-'){
		echo $core->getPageParam("js_list_after");
	}
?>
</body>
</html>