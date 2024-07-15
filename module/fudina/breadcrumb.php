<?php
	$core->addMethod('getParentList', function($symbol){
		if(isset($this->parent[$symbol])){
			if($this->parent[$symbol]["parent"]==="-"){
				return null;
			}else{
				$symbol_list=array();
				
				$parent_list=$this->getParentList($this->parent[$symbol]["parent"]);
				if(gettype($parent_list)=="array"){
					foreach($parent_list as $p){
						array_push($symbol_list,$p);
					}
				}else if(gettype($parent_list)=="NULL"){
					array_push($symbol_list,$this->parent[$symbol]["parent"]);
				}
				array_push($symbol_list,$symbol);

				return $symbol_list;
			}
		}else{
			echo "[設定エラー]page.csvのparentの値：".$symbol."が誤っています。<br>- parentで指定されたsymbolが上の行に存在する物か確認してください。";
			exit;
		}
	});

	$core->addMethod('getBreadCrumb', function($symbol){
		$breadcrumb = "";

		$list=$this->getParentList($symbol);
		if(gettype($list)=="array"){
			$list_num=count($list);
			foreach($list as $i=>$l){
				if($this->view["lang"]==LANG_CODE[0]){
					$breadcrumb.="<a href=\"/".$l."/\">".$this->parent[$l]["title"]."</a>";
				}else{
					$breadcrumb.="<a href=\"/".$this->view["lang"]."/".$l."/\">".$this->parent[$l]["title"]."</a>";
				}
				if($i<$list_num-1){
					$breadcrumb.=" > ";
				}
			}
		}

		$this->page_param["breadcrumb"] = $breadcrumb;
	});

	$core->getBreadCrumb($core->getPageParam("symbol"));
?>