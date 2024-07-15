<?php
	namespace fudina;
	mb_internal_encoding("UTF-8");

	define("LANG_CODE", ["ja","en"]);
	define("TOP_FRAME", "empty");

	#汎用関数
	include_once("./module/fudina/core.php");
	$core = new Core();

	if(!$core->getPageParam("html_direct")){
		include_once($core->getFramePath());
	}
?>