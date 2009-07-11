/*********************************************************/
/* Enable accesskeys in IE                               */
/*********************************************************/

var accessKeyEnabled = true;

function useAccessKey (evt) {
	if (accessKeyEnabled == true) {
		if (event.altKey) {
			event.srcElement.click();
		}
	} else {
		event.srcElement.blur();
		accessKeyEnabled = true;
	}
}

function releaseAccessKey() {
	if (accessKeyEnabled == false) {
		accessKeyEnabled = true;
	}
}

function initAccessKey() {
	if (navigator.appName == "Microsoft Internet Explorer") {
		for (i=0;i<document.all.length;i++) {
			a = document.all(i);
			if (a.tagName == 'A' && a.accessKey != '') {
				a.blur();
				a.onfocus = useAccessKey;
			}
		}
		if (event.altKey) {
			accessKeyEnabled = false;
			document.onkeyup = releaseAccessKey;
			setTimeout ('releaseAccessKey()', 100);
		}
	}
}


/*********************************************************/
/* General functions                                     */
/*********************************************************/

function findObj(n, d) { 
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function openWindow(theURL,winName,features) {
  window.open(theURL,winName,features);
  return false;
}

function setTextOfLayer(objName,newText) {
	while (obj = document.getElementById(objName)) {
		with (obj)
			if (document.layers) {document.write(unescape(newText)); document.close();}
			else innerHTML = unescape(newText);
		obj.id = '';
	}
}

function showLayer(obj) { 
	if (obj.style) 
		obj=obj.style; 
    
	obj.display = 'block'
}

function hideLayer(obj) { 
	if (obj.style) 
		obj=obj.style; 
    
	obj.display = 'none'
}


/*********************************************************/
/* Confirm form submit                                   */
/*********************************************************/

function confirm_submit(o, str)
{
	f = findObj(o);
	if(confirm(str)) f.submit();
}



/*********************************************************/
/* Open Search window                                    */
/*********************************************************/

function search_window(keyword, where)
{
	path = where+'?keyword='+keyword;
	SearchWindow = window.open("","Search","toolbar=no,location=no,status=no,scrollbars=yes,width=600,height=500,innerheight=50,screenX=100,screenY=100,pageXoffset=100,pageYoffset=100,resizable=yes");          

	if (SearchWindow.frames.length == 0) 
	{
		SearchWindow = window.open(path,"Search","toolbar=no,location=no,status=no,scrollbars=yes,width=700,height=600,innerheight=50,screenX=100,screenY=100,pageXoffset=100,pageYoffset=100,resizable=yes");
	}
	else
	{
		SearchWindow.location.href = path;
		SearchWindow.focus();
	}
}



/*********************************************************/
/* Focus the first field of the login screen             */
/*********************************************************/

function login_focus()
{
	if (document.login.phpAds_username.value == '')
		document.login.phpAds_username.focus();
	else
		document.login.phpAds_password.focus();
}


/*********************************************************/
/* Copy the contents of a field to the clipboard         */
/*********************************************************/

function phpAds_CopyClipboard(obj)
{
	obj = findObj(obj);
	
	if (obj) {
		window.clipboardData.setData('Text', obj.value);
	}
}


/*********************************************************/
/* Setup boxrow handlers                                 */
/*********************************************************/

function boxrow_init()
{
	var obj = document.body.getElementsByTagName("DIV");

	for (var i=0; i < obj.length; i++)
	{
		if (obj[i].className == 'boxrow')
		{
			obj[i].onmouseover = boxrow_over;
			obj[i].onmouseout = boxrow_leave;
			obj[i].onclick = boxrow_click;

			// Check for 1st generation childs -- input tags
			j = 0;

			while (j < obj[i].childNodes.length)
			{
				if (obj[i].childNodes[j].tagName == 'INPUT')
				obj[i].childNodes[j].onclick = boxrow_nonbubble;

				j++;
			}
		}
	}
}

function boxrow_over(e)
{
	if (!e && window.event)
		e = window.event;

	if (e.srcElement)
		o = e.srcElement;
	else
		o = e.target;

	// Find the DIV
	while (o.tagName != "DIV")
		o = o.parentNode;

		o.style.backgroundColor='#F6F6F6';
}

function boxrow_leave(e)
{
	if (!e && window.event)
		e = window.event;

	if (e.srcElement)
		o = e.srcElement;
	else
		o = e.target;

	// Find the DIV
	while (o.tagName != "DIV")
		o = o.parentNode;

	o.style.backgroundColor='#FFFFFF';
}

function boxrow_click(e)
{
	if (!e && window.event)
		e = window.event;

	if (e.srcElement)
		o = e.srcElement;
	else
		o = e.target;

	// Find the DIV
	while (o.tagName != "DIV")
		o = o.parentNode;

	// Find the checkbox
	i = 0;

	while (i < o.childNodes.length)
	{
		if (o.childNodes[i].tagName == 'INPUT')
		{
			o.childNodes[i].checked = !o.childNodes[i].checked;
			return true;
		}

		i++;
	}
}

function boxrow_nonbubble(e)
{
	if (!e && window.event)
    e = window.event;
  
	if (e.stopPropagation) 
		e.stopPropagation(); 
	else 
		e.cancelBubble = true; 
}

function cascadebox_change(o)
{
	var sel = o.options[o.selectedIndex].value;
	var baseid = o.id + '_';
	var matchid = baseid + (sel ? sel + '_' : '');
	var obj = document.body.getElementsByTagName("DIV");
	
	for (var i=0; i < obj.length; i++)
	{
		if (obj[i].id.indexOf(baseid) == 0)
		{
			obj[i].style.display = obj[i].id.indexOf(matchid) == 0 ? '' : 'none';
		}
	}
}


/*********************************************************/
/* Setup all event handlers for use on the page          */
/*********************************************************/

function initPage()
{
	initAccessKey();
	boxrow_init();
}

// Settings
var helpSteps = 12;
var helpStepHeight = 8;
var helpDefault = '';

var helpCounter = 0;
var helpSpeed = 1;
var helpLeft = 181;
var helpOnScreen = false;
var helpTimerID = null;



/*********************************************************/
/* Set the help text                                     */
/*********************************************************/

function setHelp(item)
{
	var helpContents = findObj("helpContents");

	if (helpOnScreen == true)
	{
		if (item != null && helpArray[item] != null)
			helpContents.innerHTML = unescape(helpArray[item]);
		else
			helpContents.innerHTML = helpDefault;			
	}
}



/*********************************************************/
/* Toggle the help popup                                 */
/*********************************************************/

function toggleHelp()
{
	if (helpOnScreen == false)
		displayHelp();
	else
		hideHelp();
}



/*********************************************************/
/* Display the help popup                                */
/*********************************************************/

function displayHelp()
{
	var helpLayer = findObj("helpLayer");
	if (helpLayer.style) helpLayer = helpLayer.style;
	
	if (document.all && !window.innerHeight) { 
		helpLayer.pixelWidth = document.body.clientWidth - helpLeft;
		helpLayer.pixelHeight = helpStepHeight;
		helpLayer.pixelTop = document.body.clientHeight + document.body.scrollTop - helpStepHeight;
	} else 	{
		helpLayer.width = document.width - helpLeft;
		helpLayer.height = helpStepHeight;
		helpLayer.top = window.innerHeight + window.pageYOffset - helpStepHeight;
	}
	helpLayer.visibility = 'visible';

	helpCounter = 1;
	setTimeout('growHelp()', helpSpeed);
	
	var helpContents = findObj("helpContents");
	helpDefault = helpContents.innerHTML;
}

function growHelp()
{
	helpCounter++;

	var helpLayer = findObj("helpLayer");
	if (helpLayer.style) helpLayer = helpLayer.style;

	if (document.all && !window.innerHeight) {
		helpLayer.pixelHeight = helpCounter * helpStepHeight;
		helpLayer.pixelTop = document.body.clientHeight + document.body.scrollTop - (helpCounter * helpStepHeight);
	} else {
		helpLayer.height = helpCounter * helpStepHeight;
		helpLayer.top = window.innerHeight + window.pageYOffset - (helpCounter * helpStepHeight);
		if (helpTimerID == null) helpTimerID = setInterval('resizeHelp()', 100);
	}
	
	if (helpCounter < helpSteps)
		setTimeout('growHelp()', helpSpeed);
	else
		helpOnScreen = true;
}



/*********************************************************/
/* Hide the help popup                                   */
/*********************************************************/

function hideHelp()
{
	helpOnScreen = false;
	helpCounter = helpSteps;		
	setTimeout('helpShrink()', helpSpeed);
}

function helpShrink()
{
	var helpLayer = findObj("helpLayer");
	if (helpLayer.style) helpLayer = helpLayer.style;

	helpCounter--;
	
	if (helpCounter >= 0) 
	{
		if (document.all && !window.innerHeight) {
			helpLayer.pixelHeight = helpCounter * helpStepHeight;
			helpLayer.pixelTop = document.body.clientHeight + document.body.scrollTop - (helpCounter * helpStepHeight);
		} else {
			helpLayer.height = helpCounter * helpStepHeight;
			helpLayer.top = window.innerHeight + window.pageYOffset - (helpCounter * helpStepHeight);
		}
		setTimeout('helpShrink()', helpSpeed);
	} 
	else 
	{
		if (document.all && !window.innerHeight) {
			helpLayer.pixelHeight = 1;
			helpLayer.pixelTop = document.body.clientHeight + document.body.scrollTop - 1;
		} else {
			helpLayer.height = 1;
			helpLayer.top = window.innerHeight + window.pageYOffset - 1;
		}
		helpLayer.visibility = 'hidden';
		
		var helpContents = findObj("helpContents");
		helpContents.innerHTML = helpDefault;
	}
}



/*********************************************************/
/* Resize the help popup                                 */
/*********************************************************/

function resizeHelp()
{	
	if (helpOnScreen == true) 
	{
		var helpLayer = findObj("helpLayer");
		if (helpLayer.style) helpLayer = helpLayer.style;

		if (document.all && !window.innerHeight) {
			helpLayer.pixelHeight = helpSteps * helpStepHeight;
			helpLayer.pixelWidth = document.body.clientWidth - helpLeft;
			helpLayer.pixelTop = document.body.clientHeight + document.body.scrollTop - (helpSteps * helpStepHeight);
		} else {
			helpLayer.height = helpSteps * helpStepHeight;
			helpLayer.width = document.width - helpLeft;
			helpLayer.top = window.innerHeight + window.pageYOffset - (helpSteps * helpStepHeight);
		}
	}
}

