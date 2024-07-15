const modal = document.querySelector(".fujimodal");
const modal_background = document.querySelector(".fujimodal-background");
const modal_content = document.querySelector(".fujimodal-content");

function openFujiModal(){
  modal.style.opacity=100;
  setTimeout(function(){
    modal.style.display="flex";
    adjustWindow();
  },300);
}    

function closeFujiModal(){
  modal.style.opacity=0;
  setTimeout(function(){
    modal.style.display="none";
    adjustWindow();
  },300);
}

modal_background.addEventListener("click",function(){
  closeFujiModal();
});


function adjustWindow(){
  if(modal_content.clientHeight>window.innerHeight){
    modal.style.alignItems="normal";
  }else{
    modal.style.alignItems="center";
  }
}