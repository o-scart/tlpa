TLPA.ui.setauth_0 = function()
{
	var bs = "";
	
	bs += "<h1>Stage 1: Password</h1><br>";
	bs += "<form class='form'>";
	bs += "Password:<br><br> <input type='text' placeholder='Please enter a password' id='in_pw' value=''/>";
	bs += "<button type='button' class='btn btn-success' id='send_pw'>Send</button>";
	bs += "<a href='#set_auth#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "";
	
	getContentManager().addToContent(bs);
};