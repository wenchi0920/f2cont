<!--
var objMmInfo = null;
var intMmCnt = 0;
var intSelMmCnt = 0;
var intActMmCnt = 0;
var cActIdx = 0;
var cActTit = "nAnT";
var blnfpl = false;
var blnEnabled = false;
var blnEOT = false;
var arrSelMm = null;
var arrActMm = null;
var intExobudStat = 0;
var tidTLab = null;
var tidErr = null;
var tidMsg = null;
var intErrCnt = 0;
var blnRept = false;
var blnAutoProc = true;
var blnElaps = true;
var intDelay = 500;

function wmpInit(){
var wmps = Exobud.settings;
var wmpc = Exobud.ClosedCaption;
wmps.autoStart = true;
wmps.balance = 0;
wmps.enableErrorDialogs = false;
wmps.invokeURLs = false;
wmps.mute = false;
wmps.playCount = 1;
wmps.rate = 1;
wmps.volume = 100;
Exobud.enabled = true;
}

function mkMmPath(u,t,f){
this.mmUrl = u;
this.mmTit = t;
this.mmDur = 0;
this.selMm = f;
this.actMm = f;
}

function mkList(u,t,f){
var cu = u;
var ct = t;
var cf = f;
var idx = 0;
if(objMmInfo == null){objMmInfo=new Array(); idx=0;}
else {idx=objMmInfo.length;}
if(u=="" || u==null){cu="mms://";}
if(t=="" || t==null){ct="nAnT";}
if(f=="f" || f=="F"){cf="f";}
else {cf="t"; intSelMmCnt++;}
objMmInfo[idx]=new mkMmPath(cu,ct,cf);
intActMmCnt = intSelMmCnt;
intMmCnt = objMmInfo.length;
}

function mkSel(){
arrSelMm = null;
intSelMmCnt = 0;
var selidx = 0;
if(intMmCnt<=0){intExobudStat=1; blnEnabled=false; return;}
arrSelMm = new Array();
for(var i=0; i<intMmCnt; i++){
if(objMmInfo[i].selMm =="t"){arrSelMm[selidx]=i;selidx++;}
}
intSelMmCnt=arrSelMm.length;
if(intSelMmCnt<=0){blnEnabled=false; intExobudStat=2; arrSelMm=null; return;}
else {blnEnabled=true; mkAct();}
}

function mkAct(){
arrActMm = null;
intActMmCnt = 0;
var selidx = 0;
var actidx = 0;
if(blnEnabled){
arrActMm=new Array();
for(var i=0; i<intSelMmCnt; i++){
selidx=arrSelMm[i];
if(objMmInfo[selidx].actMm=="t"){arrActMm[actidx]=selidx; actidx++;}
}
intActMmCnt=arrActMm.length;
}
else { return;}
if(intActMmCnt<=0){blnEOT=true;arrActMm=null;}
else {blnEOT=false;}
}

function chkAllSel(){
for(var i=0; i<intMmCnt; i++){
objMmInfo[i].selMm="t";
objMmInfo[i].actMm="t";
}
mkSel();
}

function chkAllDesel(){
for(var i=0; i<intMmCnt; i++){
objMmInfo[i].selMm="f";
objMmInfo[i].actMm="f";
}
mkSel();
}

function chkItemSel(idx){
if(objMmInfo[idx].selMm =="t"){
objMmInfo[idx].selMm="f";objMmInfo[idx].actMm="f";
} else {
objMmInfo[idx].selMm="t";objMmInfo[idx].actMm="t";
}
mkSel();
}

function chkItemAct(idx){
objMmInfo[idx].actMm="f";
mkAct();
}

function mkSelAct(){
var idx=0;
for(var i=0; i<intSelMmCnt; i++){
idx=arrSelMm[i];
objMmInfo[idx].actMm="t";
}
mkAct();
}

function initExobud(){
wmpInit();
mkSel();
blnfpl = false;
if(!blnShowVolCtrl) {
document.images['vmute'].style.display = "none";
document.images['vdn'].style.display = "none";
document.images['vup'].style.display = "none";
}
if(!blnShowPlist){ document.images['plist'].style.display = "none";}

showTLab();

if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "GTMusic V1.0";
if(blnStatusBar){ window.status=('GTMusic V1.0');}
if(blnAutoStart){startExobud();}
}

function startExobud(){
var wmps = Exobud.playState;
if(wmps==2){Exobud.controls.play(); return;}
if(wmps==3){ return;}
blnfpl=false;
if(!blnEnabled){waitMsg();return;}
if(blnEOT){mkSelAct();}
if(intErrCnt>0){intErrCnt=0;tidErr=setTimeout('retryPlay(),1000');return;}
if(blnRndPlay){rndPlay();}
else {cActIdx=arrActMm[0]; selMmPlay(cActIdx);}
}

function selMmPlay(idx){
clearTimeout(tidErr);
cActIdx=idx;
var trknum=idx+1;
var ctit =objMmInfo[idx].mmTit;
if(ctit=="nAnT"){ctit="(没有媒体标题)"}
Exobud.URL = objMmInfo[idx].mmUrl;
cActTit = "" + trknum + ". " + ctit;
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = cActTit;
disp1.innerHTML = cActTit;
if(blnStatusBar){ window.status=(cActTit);}
chkItemAct(cActIdx);
}

function wmpPlay(){Exobud.controls.play();}

function wmpStop(){
intErrCnt=0;
clearTimeout(tidErr);
clearInterval(tidTLab);


showTLab();
mkSelAct();
Exobud.controls.stop();
Exobud.close();
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "GTMusic V1.0 [Ready]";
if(blnStatusBar){ window.status=('GTMusic V1.0 [Ready]');return true;}
}

function wmpPause(){Exobud.controls.pause();}

function wmpPP(){
var wmps = Exobud.playState;
var wmpc = Exobud.controls;
clearInterval(tidTLab);
clearTimeout(tidMsg);
if(wmps==2){wmpc.play();}
if(wmps==3){wmpc.pause(); if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="Pause"; tidMsg=setTimeout('rtnTLab()',1500);}
return;
}

function rndPlay(){
if(!blnEnabled){waitMsg();return;}
intErrCnt=0;
var idx=Math.floor(Math.random() * intActMmCnt);
cActIdx=arrActMm[idx];
selMmPlay(cActIdx);
}

function playAuto(){
if(blnRept){selMmPlay(cActIdx);return;}
if(!blnAutoProc){wmpStop();return;}
if(blnfpl){wmpStop();return;}
if(!blnEnabled){wmpStop();return;}
if(blnEOT){
if(blnLoopTrk){startExobud();}
else {wmpStop();}
} else {
if(blnRndPlay){rndPlay();}
else {cActIdx=arrActMm[0]; selMmPlay(cActIdx);}
}
}

function selPlPlay(idx){
blnfpl=true;
selMmPlay(idx);
}

function playPrev(){
var wmps = Exobud.playState;
if(wmps==2 || wmps==3){Exobud.controls.stop();}
blnfpl=false;
if(!blnEnabled){waitMsg();return;}
if(blnEOT){mkSelAct();}
intErrCnt=0;
if(blnRndPlay){rndPlay();}
else {
var idx=cActIdx;
var blnFind=false;
for(var i=0;i<intSelMmCnt;i++){ if(cActIdx==arrSelMm[i]){idx=i-1; blnFind=true;}}
if(!blnFind){startExobud();return;}
if(idx<0){idx=intSelMmCnt-1;cActIdx=arrSelMm[idx];}
else {cActIdx=arrSelMm[idx];}
selMmPlay(cActIdx);
}
}

function playNext(){
var wmps = Exobud.playState;
if(wmps==2 || wmps==3){Exobud.controls.stop();}
blnfpl=false;
if(!blnEnabled){waitMsg();return;}
if(blnEOT){mkSelAct();}
intErrCnt=0;
if(blnRndPlay){rndPlay();}
else {
var idx=cActIdx;
var blnFind=false;
for(var i=0;i<intSelMmCnt;i++){ if(cActIdx==arrSelMm[i]){idx=i+1; blnFind=true;}}
if(!blnFind){startExobud();return;}
if(idx>=intSelMmCnt){idx=0;cActIdx=arrSelMm[idx];}
else {cActIdx=arrSelMm[idx];}
selMmPlay(cActIdx);
}
}

function retryPlay(){
selMmPlay(cActIdx);
}

function chkRept(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
if(blnRept){
blnRept=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="不重复播放";
} else {
blnRept=true; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="重复播放";
}
tidMsg=setTimeout('rtnTLab()',1000);
}

function chgPMode(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
if(blnRndPlay){
blnRndPlay=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="循环播放";
} else {
blnRndPlay=true; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="随机播放";
}
tidMsg=setTimeout('rtnTLab()',1000);
}

function evtPSChg(f){
switch(f){
case 1:
evtStop();
break;
case 2:
evtPause();
break;
case 3:
evtPlay();
break;
case 8:
setTimeout('playAuto()', intDelay);
break;
}
}

function evtWmpBuff(f){
if(f){
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = "Loading...";
var msg = "(Loading...) " + cActTit;
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = msg;
if(blnStatusBar){ window.status=(msg);}
} else {
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = cActTit; showTLab();
}
}

function evtWmpError(){
intErrCnt++;
Exobud.Error.clearErrorQueue();
if(intErrCnt<=3){
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = "尝试连接 (" + intErrCnt + ")";
var msg = "(尝试第 " + intErrCnt + " 次连接媒体) " + cActTit;
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "<连接失效> " + cActTit;
if(blnStatusBar){ window.status=(msg);}
tidErr=setTimeout('retryPlay()',1000);
} else {
clearTimeout(tidErr);
intErrCnt=0;showTLab();
var msg = "放弃此首音乐，连接下一首";
if(blnStatusBar){ window.status=(msg);}
setTimeout('playAuto()',1000);}
}

function evtStop(){
clearTimeout(tidErr);
clearInterval(tidTLab);
showTLab();
intErrCnt=0;


if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "GTMusic V1.0 [Waiting For The Next]";
if(blnStatusBar){ window.status=('GTMusic V1.0 [Waiting For The Next]');return true;}
}

function evtPause(){

clearInterval(tidTLab);
showTLab();
}

function evtPlay(){

tidTLab=setInterval('showTLab()',1000);
}

function showTLab(){
var ps = Exobud.playState;
if(ps==2 || ps==3){
var cp=Exobud.controls.currentPosition;
var cps=Exobud.controls.currentPositionString;
var dur=Exobud.currentMedia.duration;
var durs=Exobud.currentMedia.durationString;
if(blnElaps){
  if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = disp1.innerHTML ;
  if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = cps + " | " + durs;
var msg = cActTit + " (" + cps + " | " + durs + ")";
if(ps==2){msg = "(Pause) " + msg;}
if(blnStatusBar){ window.status=(msg);return true;}
} else {
var laps = dur-cp;
var strLaps = wmpTime(laps);
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = disp1.innerHTML ;
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = strLaps + " | " + durs;
var msg = cActTit + " (" + strLaps + " | " + durs + ")";
if(ps==2){msg = "(Pause) " + msg;}
if(blnStatusBar){ window.status=(msg);return true;}
}
} else {
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = "";
if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "<img src='Plugins/GTmusic/conn/waiting.gif'>" ;
}
}

function chgTimeFmt(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
if(blnElaps){
blnElaps=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="倒数方式";
} else {
blnElaps=true; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="正常方式";
}
tidMsg=setTimeout('rtnTLab()',1000);
}

function rtnTLab(){
clearTimeout(tidMsg);
var wmps = Exobud.playState;
if(wmps==3){tidTLab=setInterval('showTLab()',1000);}
else {showTLab();}
}

function wmpTime(dur){
var hh, min, sec, timeLabel;
hh=Math.floor(dur/3600);
min=Math.floor(dur/60)%60;
sec=Math.floor(dur%60);
if(isNaN(min)){ return "00:00";}
if(isNaN(hh) || hh==0){timeLabel="";}
else {
if(hh>9){timeLabel = hh.toString() + ":";}
else {timeLabel = "0" + hh.toString() + ":";}
}
if(min>9){timeLabel = timeLabel + min.toString() + ":";}
else {timeLabel = timeLabel + "0" + min.toString() + ":";}
if(sec>9){timeLabel = timeLabel + sec.toString();}
else {timeLabel = timeLabel + "0" + sec.toString();}
return timeLabel;
}

var vmax = 100;
var vmin = 0;
var vdep = 10;

function wmpVolUp(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
var ps = Exobud.settings;
if(ps.mute){ps.mute=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="音量恢复"; }
else {
if(ps.volume >= (vmax-vdep)){ps.volume = vmax;}
else {ps.volume = ps.volume + vdep;}
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = "音量: " + ps.volume + "%";
}
tidMsg=setTimeout('rtnTLab()',1000);
}

function wmpVolDn(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
var ps = Exobud.settings;
if(ps.mute){ps.mute=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="音量恢复"; }
else {
if(ps.volume <= vdep){ps.volume = vmin;}
else {ps.volume = ps.volume - vdep;}
if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML = "音量: " + ps.volume + "%";
}
tidMsg=setTimeout('rtnTLab()',1000);
}

function wmpMute(){
var wmps = Exobud.playState;
if(wmps==3){clearInterval(tidTLab);}
var ps = Exobud.settings;
if(!ps.mute){
ps.mute=true; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="进入静音模式";
} else {
ps.mute=false; if((typeof eval(parent.Greentea.disp2))!="undefined")parent.Greentea.disp2.innerHTML="退出静音模式"; 
}
tidMsg=setTimeout('rtnTLab()',1000);
}


function waitMsg(){
if(intExobudStat==1){if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "无法播放 － 播放清单上没有设定任何曲目。";}
if(intExobudStat==2){if((typeof eval(parent.Greentea.disp1))!="undefined")parent.Greentea.disp1.innerHTML = "无法播放 － 您没有选取清单上任何一首曲目。";}
if(blnStatusBar){
if(intExobudStat==1){ window.status=('无法播放 － 播放清单上没有设定任何曲目。'); return true;}
if(intExobudStat==2){ window.status=('无法播放 － 您没有选择清单中任何一首曲目。'); return true;}
}
}

function openPlist(){
window.open("plugins/GTMusic/conn/mpl.htm","mplist","top=120,left=320,width=350,height=480,scrollbars=no,resizable=yes,copyhistory=no");
}

function chkWmpState(){
return Exobud.playState;
}

function chkWmpOState(){
return Exobud.openState;
}

function chkOnline(){
return Exobud.isOnline;
}

function vizExobud(){
}
//-->