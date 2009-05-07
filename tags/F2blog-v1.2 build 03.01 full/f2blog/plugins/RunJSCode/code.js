function copyCode(obj) {
	var rng = document.body.createTextRange();
	rng.moveToElementText(obj);
	rng.scrollIntoView();
	rng.select();
	rng.execCommand("Copy");
	rng.collapse(false);
}

function runCode(obj) {
	var winname = window.open('', "_blank", '');
	winname.document.open('text/html', 'replace');
	winname.document.writeln(obj.value);
	winname.document.close();
}

function saveCode(obj) {
	var winname = window.open('', '_blank', 'top=10000');
	winname.document.open('text/html', 'replace');
	winname.document.writeln(obj.value);
	winname.document.execCommand('saveas','','code.htm');
	winname.close();
}
