<?php
	namespace fudina;

	trait functionLoader
	{
	    private $__dynamicMethods = [];

	    public function addMethod(string $name, \Closure $method)
	    {
	        $this->__dynamicMethods[$name] = $method->bindTo($this, self::class);
	    }

	    public function __call(string $name, array $arguments)
	    {
	        if (!array_key_exists($name, $this->__dynamicMethods)) {
	            throw new \BadMethodCallException(
	                'Call to undefined method ' . __CLASS__ . "::$name()"
	            );
	        }
	        return $this->__dynamicMethods[$name](...$arguments);
	    }
	}

	class Core{
		use functionLoader;
		
		//使用変数初期化
		private $view=array(); #表示形式格納配列
		private $page_param=array();
		private $has_index=false;
		private $has_toppage=false;
		private $row;
		private $parent=array();

		private $top_page_param=null;#トップページのデータ格納
		private $page_status_num=array("undefind"=>0,"found"=>1,"not_found"=>2,"hidden"=>3,"testing"=>4);

		private $frame_path=null; #フレームのパス
		static private $_methods = array();  

		private $page=null; #ページクエリ
		private $file = null; #データべースのファイルポインタ

		private function findLangList(){
			if(file_exists("dimensions/pc/pages/$this->page/hreflang.txt")){
				return file_get_contents("dimensions/pc/pages/$this->page/hreflang.txt");
			}else{
				return '-';
			}
		}

		private function findCssList(){
			if(file_exists("dimensions/".$this->view["dimension"]."/pages/$this->page/local_css.txt")){
				$css_list=file_get_contents("dimensions/".$this->view["dimension"]."/pages/$this->page/local_css.txt");
				$css_list=str_replace("[this]","/dimensions/".$this->view["dimension"]."/pages/".$this->page."/",$css_list);
				return $css_list;
			}else{
				return '-';
			}
		}

		private function findJsList($type){
			if(file_exists("dimensions/".$this->view["dimension"]."/pages/$this->page/local_js$type.txt")){
				$js_list=file_get_contents("dimensions/".$this->view["dimension"]."/pages/$this->page/local_js$type.txt");
				$js_list=str_replace("[this]","/dimensions/".$this->view["dimension"]."/pages/".$this->page."/",$js_list);
				return $js_list;
			}else{
				return '-';
			}
		}

		#表示ページをトップに修正
		private function set_page2top(){
			$this->page="top";
			$this->page_param["symbol"]="top";
			$this->page_param["title"]=$this->top_page_param[$this->{"r_title"}];
			$this->page_param["description"]=$this->top_page_param[$this->{"r_description"}];
			$this->page_param["keywords"]=$this->top_page_param[$this->{"r_keywords"}];
			$this->page_param["url"]="http://www.fujiformat.com/";
			$this->page_param["breadcrumb"]="";
			$this->page_param["lang_list"]=$this->findLangList();
			$this->page_param["css_list"]=$this->findCssList();
			$this->page_param["js_list_before"]=$this->findJsList("_before");
			$this->page_param["js_list_after"]=$this->findJsList("_after");
			$this->frame_path="dimensions/".$this->view["dimension"]."/frames/".TOP_FRAME."/frame.php";
		}

		#データのUTF8化
		private function encodeArray($array){
			foreach($array as $i=>$a){
				if(gettype($a)=="array"){
					$array[$i]=encodeArray($a);
				}else{
					$encode=mb_detect_encoding($a);
					if($encode!="utf-8"){
						$array[$i]=mb_convert_encoding($a,"utf-8");
					}
				}
			}
		}

		public function getPageParam($index){
			return $this->page_param[$index];
		}

		public function getFramePath(){
			return $this->frame_path;
		}

		public function getPageStatusNum($index){
			return $this->page_status_num[$index];
		}

		public function getView($index){
			return $this->view[$index];
		}

		private function fgetcsv_reg(&$handle,$length=NULL,$d=',',$e='"'){
			$d=preg_quote($d);
			$e=preg_quote($e);
			$_line="";
			$eof=false;
			while(($eof!=true) && (!feof($handle))){
				$_line.=(empty($length) ? fgets($handle) : fgets($handle,$length));
				$itemcnt=preg_match_all('/'.$e.'/',$_line,$dummy);
				if($itemcnt%2==0){
					$eof=true;
				}
			}
			$_csv_line=preg_replace('/(?:\\r\\n|[\\r\\n])?$/',$d,trim($_line));
			$_csv_pattern='/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
			preg_match_all($_csv_pattern,$_csv_line,$_csv_matches);
			$_csv_data=$_csv_matches[1];
			for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
				$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
				$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
			}
			return empty($_line) ? false : $_csv_data;
		}

		function __construct(){
			$this->view["ua"]=$this->view["dimension"]=null;

			// 表示形式決定
			#ユーザーエージェント調査
			if(isset($_SERVER['HTTP_USER_AGENT'])){
				$this->view["ua"]=$_SERVER['HTTP_USER_AGENT'];
				if((strpos($this->view["ua"], 'Android') !== false) && (strpos($this->view["ua"], 'Mobile') !== false) || (strpos($this->view["ua"], 'iPhone') !== false) || (strpos($this->view["ua"], 'Windows Phone') !== false)|| (strpos($this->view["ua"], 'iPad') !== false) || (strpos($this->view["ua"], 'iPod') !== false) || (strpos($this->view["ua"], 'tablet') !== false)){
					$this->view["ua"]="sp";
				}else{
					$this->view["ua"]="pc";
				}
			}else{
				$this->view["ua"]="pc";
			}

			#表示ページを確定
			if(isset($_REQUEST["page_var1"])){
				if(is_int(array_search($_REQUEST["page_var1"], LANG_CODE))){
					$this->view["lang"]=$_REQUEST["page_var1"];
					if($this->view["lang"]==LANG_CODE[0]){
						$this->view["dimension"]=$this->view["ua"];
					}else{
						$this->view["dimension"]=$this->view["lang"]."_".$this->view["ua"];
					}

					if(isset($_REQUEST["page_var2"])){
						$this->page = $_REQUEST["page_var2"];
					}else{
						$this->page="top";
					}
				}else{
					$this->page = $_REQUEST["page_var1"];
					$this->view["lang"]=LANG_CODE[0];
					$this->view["dimension"]=$this->view["ua"];
				}
			}else{
				$this->page = "top";
				$this->view["lang"]=LANG_CODE[0];
				$this->view["dimension"]=$this->view["ua"];
			}

			$this->page_param["status"]=$this->page_status_num["undefind"];#ページのステータス
			$this->page_param["html_direct"]=false;

			#生成済みhtmlを確認
			if(file_exists("dimensions/".$this->view["dimension"]."/pages/".$this->page."/page.html")){
				include_once "dimensions/".$this->view["dimension"]."/pages/".$this->page."/page.html";
				$this->page_param["html_direct"]=true;
				exit;
			}else{
				if(file_exists("dimensions/".$this->view["dimension"]."/pages.csv")){
					$this->file=fopen("dimensions/".$this->view["dimension"]."/pages.csv","r");
					while(!feof($this->file)){
						$this->row = $this->fgetcsv_reg($this->file);

						#列名変数を設定
						if(!$this->has_index&&$this->row[0]==="show"){
							foreach($this->row as $r=>$i){
								$this->{"r_".$i} = $r;
							}
							$this->has_index=true;
							continue;
						}

						#トップページのページ情報を格納
						if(!$this->has_toppage&&$this->row[$this->{"r_symbol"}]==="top"){
							$this->top_page_param=$this->row;
							$this->has_toppage=true;
						}

						#サイト一覧の編成
						if($this->row[$this->{"r_show"}]==="o"||$this->row[$this->{"r_show"}]==="t"){
							$this->parent[$this->row[$this->{"r_symbol"}]]=array("parent"=>$this->row[$this->{"r_parent"}], "title"=>$this->row[$this->{"r_title"}]);
						}

						#表示ページ情報が該当したとき
						if($this->row[$this->{"r_symbol"}]===$this->page){
							if($this->row[$this->{"r_show"}]==="o"||$this->row[$this->{"r_show"}]==="t"){
								if(file_exists("dimensions/".$this->view["dimension"]."/pages/$this->page/page.php")){
									$this->page_param["symbol"]=$this->page;
							
									$this->page_param["title"]=$this->row[$this->{"r_title"}];
									$this->page_param["description"]=$this->row[$this->{"r_description"}];
									$this->page_param["keywords"]=$this->row[$this->{"r_keywords"}];

									$this->page_param["lang_list"]=$this->findLangList();
									$this->page_param["css_list"]=$this->findCssList();
									$this->page_param["js_list_before"]=$this->findJsList("_before");
									$this->page_param["js_list_after"]=$this->findJsList("_after");

									$this->frame_path="dimensions/".$this->view["dimension"]."/frames/".$this->row[$this->{"r_frame"}]."/frame.php";

									if($this->row[$this->{"r_show"}]==="o"){
										#ページが公開中の場合
										$this->page_param["status"]=$this->page_status_num["found"];
									}else{
										#ページがテスト公開中の場合
										$this->page_param["status"]=$this->page_status_num["testing"];
									}
								}
							}else if($this->row[$this->{"r_show"}]==="-"){
								#ページが非公開の場合
								$this->page_param["status"]=$this->page_status_num["hidden"];
								$this->set_page2top();
							}
							
						}
					}
					if($this->page_param["status"]===$this->page_status_num["undefind"]){
						#ページが見つからない場合
						$this->page_param["status"]=$this->page_status_num["not_found"];
						$this->set_page2top();
					}

					$this->encodeArray($this->page_param);
					fclose($this->file);
				}else{
					echo "[設定エラー]demensionにpage.csvが見当たりません";
					exit;
				}
			}
		}
	}
?>