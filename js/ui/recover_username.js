TLPA.ui.recover_username = function()
{
	var bs = "";
	
	bs += "<h1>Recovery: Username</h1><br>";
	bs += "<form class='form'>";
	bs += "<div class='rec'>";
	bs += "User name:<br><br> <input type='text' placeholer='Please enter your user name' id='in_user' value='" +""  + "'/>";
	bs += "</div>";
	bs += "<button type='button' class='btn btn-success' id='send_recover'>Send</button>";
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "<div class='reg'>";
	bs += "";
	
	getContentManager().addToContent(bs);
};