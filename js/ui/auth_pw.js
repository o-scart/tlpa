TLPA.ui.auth_pw = function()
{
	var bs = "";
	
	bs += "<h1>Stage 1: Password</h1><br>";
	bs += "<form class='form'>";
	bs += "Password:<br><br> <input type='password' placeholder='Enter your password here' id='in_pw' value=''/>"; //wieder drin
	bs += "<button type='button' class='btn btn-success' id='send_pw'>Send</button>";
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "";
	
	getContentManager().addToContent(bs);
};