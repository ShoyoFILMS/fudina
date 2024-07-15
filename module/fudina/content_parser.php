<?php
	$core->addMethod('dump_content_list', function($category,$mode="main",$limit=0){
		$tag_jp = array("page"=>"ページ","blog"=>"記事","video"=>"動画","illust"=>"イラスト",
		"travel"=>"旅行","anime"=>"アニメ","event_report"=>"イベント","game"=>"ゲーム","tech"=>"技術","meal"=>"ご飯");

		$content_list=''; //リストHTML格納
		$row=null;	//作品データを1つずつ格納

		$list_num=0; //表示数カウンタ
		$category_list=null; //各作品のカテゴリ格納

		//csvのindex(各列の背番号)を作成する変数
		$is_indexed=false;
		$colum=null;
		$colum_max=null;

		//言語の確認（設定されていないならja）
		if(isset($lang)==false)
			$lang="ja";

		if($file = fopen('module/content.csv','r')){

			while(!feof($file)){
				$row = $this->fgetcsv_reg($file);

				//CSVのindex(各列の背番号)を作成する
				if($is_indexed===false&&$row[0]==='show'){
					$colum_max=count($row);
					for($i=0; $i<$colum_max; $i++){
						$colum='r_'.$row[$i];
						$$colum=$i;
					}

					$is_indexed=true;
				}

				if(($mode=="main"&&$row[$r_show]==='m')||($mode=="all"&&($row[$r_show]==='m'||$row[$r_show]==='s'))){
					$category_list=explode(',',$row[$r_category]);
					foreach($category_list as $c){
						//カテゴリに適した作品をcontent_listに追加
						if($c==$category&&($limit==0||$limit>$list_num)){
							if($category_list[0]!='page'){
								$content_list.='<li class="has_tag">';
							}else{
								$content_list.='<li>';
							}
							if(substr($row[$r_url1],0,1)=='['){
								//サイト内ページ
								$content_list.='<a href=/'.str_replace(array('[',']'),'' ,$row[$r_url1]).'/ class="default">'.$row[$r_title].'</a>';
							}else if($row[$r_url1]!='-'){
								//サイト
								$content_list.='<a class="default" href="#" onclick="openModal(this);return false;" visual='.$row[$r_visual].' desc='.$row[$r_desc].' url1='.$row[$r_url1].' url2='.$row[$r_url2].' url3='.$row[$r_url3].' >'.$row[$r_title].'</a>';
							}
							
							//作品のタグリスト作成
							foreach($category_list as $c2){
								if(isset($tag_jp[$c2])&&($c2!='video'&&$c2!='blog'&&$c2!='illust'&&$c2!='page')){
									$content_list.='<a href="/gallery/'.$c2.'/" class="tag">'.$tag_jp[$c2].'</a>';
								}
							}

							$content_list.="</li>";
							$list_num++;
						}
					}
				}
			}
			
			//最終出力
			echo "<ul class=\"content_list content_list";
			if($category!="")
				echo "_".$category;
			echo "\">$content_list</ul>";
		}
	});

	function getTagJP($t){
		$tag_jp = array("page"=>"ページ","blog"=>"記事","video"=>"動画","illust"=>"イラスト",
		"travel"=>"旅行","anime"=>"アニメ","event_report"=>"イベント","game"=>"ゲーム","tech"=>"技術","meal"=>"ご飯");
		if(isset($tag_jp[$t])){
			return $tag_jp[$t];
		}else{
			return $t;
		}
	}
?>