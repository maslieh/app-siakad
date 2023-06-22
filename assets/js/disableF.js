/* Disable Right Click tanpa fungsi tambahan. */
  document.oncontextmenu = new Function("return false;");	 

function disableFungsi(e) { 
	if (	(e.which || e.keyCode) == 112 // F1
		|| 	(e.which || e.keyCode) == 113 // F2
		|| 	(e.which || e.keyCode) == 114 // F3
		|| 	(e.which || e.keyCode) == 115 // F4
		|| 	(e.which || e.keyCode) == 116 // F5
		|| 	(e.which || e.keyCode) == 117 // F6
		|| 	(e.which || e.keyCode) == 118 // F7
		|| 	(e.which || e.keyCode) == 119 // F8
		|| 	(e.which || e.keyCode) == 120 // F9
		|| 	(e.which || e.keyCode) == 121 // F10
		|| 	(e.which || e.keyCode) == 122 // F11
		|| 	(e.which || e.keyCode) == 123 // F12
 
	) e.preventDefault(); 
};