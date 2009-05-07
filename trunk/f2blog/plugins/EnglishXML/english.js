 var ETimer=null
 var ESpeed=1
 var Eopacity=1
 var EPerSpeed=0.2
 var TempE
 var TempC
 var ETag=-1
 
 function ChangeEnglish(E,C){
  TempE=E;
  TempC=C;
  if (ETimer!=null) window.clearInterval(ETimer);
  ETimer=window.setInterval("FadeFF()",2);
 }
 
 function FadeFF(){
   Eopacity+=ETag*EPerSpeed
   if (Eopacity<0) {
     document.getElementById("EnglishDiv").innerHTML=TempE
     document.getElementById("ChineseDiv").innerHTML=TempC
     ETag=1
     Eopacity=0
   }
   if (Eopacity>1){
     ETag=-1
     window.clearInterval(ETimer)
     Eopacity=1
   }
    if (document.all)
     {
       document.getElementById("EnglishDiv").filters.alpha.opacity=Eopacity*100
       document.getElementById("ChineseDiv").filters.alpha.opacity=Eopacity*100
     }
      else
     {
       document.getElementById("EnglishDiv").style.MozOpacity=Math.min(parseFloat(Eopacity),0.99)
       document.getElementById("ChineseDiv").style.MozOpacity=Math.min(parseFloat(Eopacity),0.99)
     }
 }
 
 //-------------------XML Class------------------
 function LoadEnglishXML(){
  var CheckXML
  var o=this
  this.url=null
  
  if (document.implementation && document.implementation.createDocument) {
		 CheckXML = document.implementation.createDocument("", "", null);
       }
  if (window.ActiveXObject) CheckXML= new ActiveXObject(E_getControlPrefix()+".XMLDOM");
  
  this.queryDB=function (e){
     o.url="plugins/EnglishXML/getEnglish.php";
	 o.Open();
  }
  
  this.Open=function (){
   o.readyState=0
   with (CheckXML){
     async=false;
     if (document.all){onreadystatechange=function(){o.CheckXML_CallBack()}}else{addEventListener("load", o.CheckXML_CallBack, false)}
     CheckXML.load(o.url);
   }
  }

  this.CheckXML_CallBack =function(){
   var E,C
     if (document.all && CheckXML.readyState!=4){return}
     if (document.all){
	     E=CheckXML.childNodes[1].childNodes[0].firstChild.nodeValue
	     C=CheckXML.childNodes[1].childNodes[1].firstChild.nodeValue
     }
     else
     {
	     E=CheckXML.firstChild.childNodes[1].firstChild.nodeValue
	     C=CheckXML.firstChild.childNodes[2].firstChild.nodeValue
    }
     ChangeEnglish(E,C)
  }
 
}


//检测系统支持的XMLDom方式
  function E_getControlPrefix() {
   var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
   var o, o2;
   for (var i = 0; i < prefixes.length; i++) {
      try {
         // try to create the objects
         o = new ActiveXObject(prefixes[i] + ".XmlHttp");
         o2 = new ActiveXObject(prefixes[i] + ".XmlDom");
         return E_getControlPrefix.prefix = prefixes[i];
      }
      catch (ex) {};
   }
 }
function InitEnglishStyle(){
	//document.write ("<style type=\"text/css\">"+EngStyle+"</style>")
}
var EnglishLoad
function StartEnglish(){
	EnglishLoad=new LoadEnglishXML()
	EnglishLoad.queryDB()
	window.setInterval("EnglishLoad.queryDB()",EngCTime*1000)
}
