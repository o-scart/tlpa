TLPA.ui.setrec_index = function()
{
	var bs = "";
	
	//bs += "<a href='#done'>zur Übersicht</a>";
	
	bs += "<h1>Recovery navigation menu</h1><br>";
	bs += "<form class='form'>";
	bs += "Navigate through the stages to change recovery information or regenerate the keyfile:<br><br>";
	bs += "<a href='#set_recover#0'><div id='navbutt' class='btn btn-success'>Email adress</div></a><br>";
	bs += "<a href='#set_recover#1'><div id='navbutt' class='btn btn-success'>Security Question</div></a><br>";
	bs += "<a href='#set_recover#2'><div id='navbutt' class='btn btn-success'>Keyfile</div></a><br>";
	
	bs += "<br><br><a href='#done'>Back to overview</a>";
	bs += "</form>";
	bs += "";
	getContentManager().addToContent(bs);
};