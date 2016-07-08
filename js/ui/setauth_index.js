TLPA.ui.setauth_index = function()
{
	var bs = "";
	
	//bs += "<a href='#done'>zur Übersicht</a>";
	
	bs += "<h1>Password navigation menu</h1><br>";
	bs += "<form class='form'>";
	bs += "Navigate through the stages to set a password:<br><br>";
	bs += "<a href='#set_auth#0'><div id='navbutt' class='btn btn-success'>Stage 1: Password</div></a><br>";
	bs += "<a href='#set_auth#1'><div id='navbutt' class='btn btn-success'>Stage 2: Color Pattern</div></a><br>";
	bs += "<a href='#set_auth#2'><div id='navbutt' class='btn btn-success'>Stage 3: Image Puzzle</div></a><br>";
	
	bs += "<br><br><a href='#done'>Back to overview</a>";
	bs += "</form>";
	bs += "";
	getContentManager().addToContent(bs);
};