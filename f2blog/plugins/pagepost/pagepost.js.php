<?php require_once "../../cache/cache_setting.php"; ?>

var pp=window.location.hash.match(/pp=\d+$/);
if(pp){
	pp=pp[0];
	var link=window.location.href.match(/(.*\?)|(.*#)/);
	link=link[0].replace(/[\?\#]+$/g,"");
	if(window.location.search.match(/(^\?p=\d+)|(\&p=\d+)/)){
		var search=window.location.search.replace(/(\&pp=\d+)|(pp=\d+)/g,"");
		link+=search+"&"+pp;
	} else if(link!="<?php echo $settingInfo['blogUrl']?>"&&link!=""){
		var link=link.replace(/(post-page-\d+\/)|(\/$)/g,"");
		link+="/post-page-"+pp.substr(3)+"/";
	} 
	window.location.replace(link);
} 

phprpc_client.create('pagepost_rpc');
pagepost_rpc.use_service('plugins/pagepost/rpc.php');
pagepost_rpc.get_pagepost_callback=function(result,args,output){
	if(result instanceof phprpc_error){
		alert(result.errno+": "+result.errstr);
	} else {
		set_innerHTML('pagepost_'+args[2],output);
	} 
} 

function pagepost(post_id,curpage,id){
	if(typeof(event)=="undefined"||!event.shiftKey){
		if(pagepost_rpc.ready){
			var hint=' &nbsp; &nbsp;<strong style="color: green;">Loading ...</strong>';
			var pagebar_top=document.getElementById('pagebar_top_'+id);
			var pagebar_bottom=document.getElementById('pagebar_bottom_'+id);
			pagebar_top.innerHTML+=hint;
			pagebar_bottom.innerHTML+=hint;
			pagepost_rpc.get_pagepost(post_id,curpage,id);
			return true;
		} else{
			alert('The PagePost RPC was not ready!');
		} 
	}else{
		return true;
	} 
} 