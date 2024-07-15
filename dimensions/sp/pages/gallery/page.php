<div class="box">
<?php
	include_once("./module/fudina/url_parser.php");
	$url_param=$core->getURLParam();
	include_once("./module/fudina/content_parser.php");

	$map="list";
	if(isset($_REQUEST["page_var3"])){
		$map = $_REQUEST["page_var3"];
	}else if(isset($_REQUEST["page_var2"])){
		$map = $_REQUEST["page_var2"];
	}else if(isset($url_param["map"])){
		$map = $url_param["map"];
	}

	if($map=='list'){
?>
	<p class="note">※こちらに出ているのは一部だけでタグで全件表示を押したりタグで絞り込むともっと出てくる可能性があります</p>
	<noscript>※Javascriptを有効にするとページ以外の外部コンテンツへも移動できます</noscript>
<?php
		echo '<h2 class="list_header">ページ</h2>';
		$core->dump_content_list('page');

		echo '<h2 class="list_header" id="videos">動画<a href="./video/" class="show_all">全件表示</a></h2>';
		$core->dump_content_list('video');
		echo '<h2 class="list_header" id="illusts">イラスト<a href="./illust/" class="show_all">全件表示</a></h2>';
		$core->dump_content_list('illust');
	}else{
		echo '<h3 class="content_title list_header">'.getTagJP($map).'（全作品表示）</h3>
		<a href="/gallery/" class="show_all_content_map">絞り込み解除（代表コンテンツを表示）</a>';
		$core->dump_content_list($map,"all");
	}

	include_once("./module/frame_parts/fujimodal.php");
?>
