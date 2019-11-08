
$(document).ready(function(e) {
    ImgIputHandler.Init();
});
var ImgIputHandler={
  facePath:[
    {faceName:"微笑",facePath:"0_微笑.gif"},
    {faceName:"撇嘴",facePath:"1_撇嘴.gif"},
    {faceName:"色",facePath:"2_色.gif"},
    {faceName:"发呆",facePath:"3_发呆.gif"},
    {faceName:"得意",facePath:"4_得意.gif"},
    {faceName:"流泪",facePath:"5_流泪.gif"},
    {faceName:"害羞",facePath:"6_害羞.gif"},
    {faceName:"闭嘴",facePath:"7_闭嘴.gif"},
    {faceName:"大哭",facePath:"9_大哭.gif"},
    {faceName:"尴尬",facePath:"10_尴尬.gif"},
    {faceName:"发怒",facePath:"11_发怒.gif"},
    {faceName:"调皮",facePath:"12_调皮.gif"},
    {faceName:"龇牙",facePath:"13_龇牙.gif"},
    {faceName:"惊讶",facePath:"14_惊讶.gif"},
    {faceName:"难过",facePath:"15_难过.gif"},
    {faceName:"酷",facePath:"16_酷.gif"},
    {faceName:"冷汗",facePath:"17_冷汗.gif"},
    {faceName:"抓狂",facePath:"18_抓狂.gif"},
    {faceName:"吐",facePath:"19_吐.gif"},
    {faceName:"偷笑",facePath:"20_偷笑.gif"},
    {faceName:"可爱",facePath:"21_可爱.gif"},
    {faceName:"白眼",facePath:"22_白眼.gif"},
    {faceName:"傲慢",facePath:"23_傲慢.gif"},
    {faceName:"饥饿",facePath:"24_饥饿.gif"},
    {faceName:"困",facePath:"25_困.gif"},
    {faceName:"惊恐",facePath:"26_惊恐.gif"},
    {faceName:"流汗",facePath:"27_流汗.gif"},
    {faceName:"憨笑",facePath:"28_憨笑.gif"},
    {faceName:"大兵",facePath:"29_大兵.gif"},
    {faceName:"奋斗",facePath:"30_奋斗.gif"},
    {faceName:"咒骂",facePath:"31_咒骂.gif"},
    {faceName:"疑问",facePath:"32_疑问.gif"},
    {faceName:"嘘",facePath:"33_嘘.gif"},
    {faceName:"晕",facePath:"34_晕.gif"},
    {faceName:"折磨",facePath:"35_折磨.gif"},
    {faceName:"衰",facePath:"36_衰.gif"},
    {faceName:"骷髅",facePath:"37_骷髅.gif"},
    {faceName:"敲打",facePath:"38_敲打.gif"},
    {faceName:"再见",facePath:"39_再见.gif"},
    {faceName:"擦汗",facePath:"40_擦汗.gif"},
  ],
  Init:function(){
    var isShowImg=false;
    $(".Input_text").focusout(function(){
      $(this).parent().css("border-color", "#cccccc");
            $(this).parent().css("box-shadow", "none");
            $(this).parent().css("-moz-box-shadow", "none");
            $(this).parent().css("-webkit-box-shadow", "none");
    });
    $(".Input_text").focus(function(){
    $(this).parent().css("border-color", "rgba(19,105,172,.75)");
        $(this).parent().css("box-shadow", "0 0 3px rgba(19,105,192,.5)");
        $(this).parent().css("-moz-box-shadow", "0 0 3px rgba(241,39,232,.5)");
        $(this).parent().css("-webkit-box-shadow", "0 0 3px rgba(19,105,252,3)");
    });
    $(".imgBtn").click(function(){
      if(isShowImg==false){
        isShowImg=true;
          $(this).parent().prev().animate({marginTop:"-125px"},300);
        if($(".faceDiv").children().length==0){
          for(var i=0;i<ImgIputHandler.facePath.length;i++){
            var imgPath = "/public/home/img/";
            $(".faceDiv").append("<img title="+ImgIputHandler.facePath[i].faceName+" src="+imgPath+ImgIputHandler.facePath[i].facePath+" />");
          }
          $(".faceDiv>img").click(function(){
                isShowImg=false;
                $(this).parent().animate({marginTop:"0px"},300);
                ImgIputHandler.insertAtCursor($(".Input_text")[0],"["+$(this).attr("title")+"]");
          });
        }
      }else{
        isShowImg=false;
          $(this).parent().prev().animate({marginTop:"0px"},300);
      }
    });
  },
  insertAtCursor:function(myField, myValue) {
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
        sel.select();
    } else if (myField.selectionStart || myField.selectionStart == "0") {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var restoreTop = myField.scrollTop;
        myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
        if (restoreTop > 0) {
            myField.scrollTop = restoreTop;
        }
        myField.focus();
        myField.selectionStart = startPos + myValue.length;
        myField.selectionEnd = startPos + myValue.length;
    } else {
        myField.value += myValue;
        myField.focus();
    }
}
}