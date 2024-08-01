<?php
	if($core->getPageParam("status")===$core->getPageStatusNum("not_found")){
		echo '<p id="page_missing" class="alert">お探しのページが見つかりませんでした。</p>';
	}else if($core->getPageParam("status")===$core->getPageStatusNum("hidden")){
		echo '<p id="page_hidden" class="alert">申し訳ありません、お探しのページは現在、非公開になっております。</p>';
	}
?>
<div class="box">
	<h1>fudina sp</h1>
	<p>
		<a href="/site/">このサイトについて</a>
		<a href="/gallery/">コンテンツ一覧</a>
	</p>
</div>