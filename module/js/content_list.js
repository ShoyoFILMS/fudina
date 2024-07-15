function detectSite(url){
	if(url.indexOf("//www.nicovideo.jp/")>=0){
		return ["niconico","ニコニコ動画へ移動"];
	}else if(url.indexOf("//www.youtube.com/")>=0){
		return ["youtube","Youtubeへ移動"];
	}else if(url.indexOf("//ch.nicovideo.jp/")>=0){
		return ["niconico","ブロマガへ移動"];
	}else if(url.indexOf("//qiita.com/")>=0){
		return ["qiita","Qiitaへ移動"];
	}else if(url.indexOf("//note.mu/")>=0){
		return ["note","noteへ移動"];
	}else if(url.indexOf("//www.pixiv.net/")>=0){
		return ["pixiv","pixivへ移動"];
	}else if(url.indexOf("//togetter")>=0){
		return ["togetter","togetterへ移動"];
	}else if(url.indexOf("//news.denfaminicogamer.jp")>=0){
		return ["denfami","電ファミニコゲーマーへ移動"];
	}else if(url.indexOf("hatenablog")>=0){
		return ["hatena","はてなブログ"];
	}else if(url.indexOf("dl:")>=0){
		return ["download","ダウンロード"];
	}else{
		return ["site","サイトへ移動"];
	}
}

function makeLink(target, e, num){
	let url=e.getAttribute('url'+num);
	
	const param=detectSite(url);
	const type=param[0];
	const label=param[1];

	target.insertAdjacentHTML('beforeend','<a href="" target="_blank" rel="noopener" class="link'+num+' '+type+'"></a>');
	if(type=="download"){
		url=url.replace("dl:","");
	}
	document.querySelector(".fujimodal .link"+num).setAttribute("href",url);
	document.querySelector(".fujimodal .link"+num).textContent=label;
}

function openModal(e){
	let modal_title;
	if(e.getAttribute('title') === null){
		modal_title=e.textContent;
	}else{
		modal_title=e.getAttribute('title'); 
	}
	document.querySelector(".fujimodal h2.title").innerHTML=modal_title;

	if(e.getAttribute('visual')!='-'){
		document.querySelector(".fujimodal img.visual").setAttribute("src",e.getAttribute('visual'));
		document.querySelector(".fujimodal img.visual").style.display="block";
	}else{
		document.querySelector(".fujimodal img.visual").style.display="none";
	}
	document.querySelector(".fujimodal .link").innerHTML="";
	makeLink(document.querySelector(".fujimodal .link"), e, 1);

	if(e.getAttribute('url2')!="-"){
		makeLink(document.querySelector(".fujimodal .link"), e, 2);
	}

	if(e.getAttribute('url3')!="-"){
		makeLink(document.querySelector(".fujimodal .link"), e, 3);
	}

	document.querySelector(".fujimodal p.desc").textContent=e.getAttribute('desc').replace("\\n","<br>");
	openFujiModal();
}