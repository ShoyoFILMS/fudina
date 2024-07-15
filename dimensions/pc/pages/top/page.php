<?php
	if($core->getPageParam("status")===$core->getPageStatusNum("not_found")){
		echo '<p id="page_missing" class="alert">お探しのページが見つかりませんでした。</p>';
	}else if($core->getPageParam("status")===$core->getPageStatusNum("hidden")){
		echo '<p id="page_hidden" class="alert">申し訳ありません、お探しのページは現在、非公開になっております。</p>';
	}
	include_once("./module/fudina/content_parser.php");
?>
<div class="box">
	<h1>fudina</h1>
</div>