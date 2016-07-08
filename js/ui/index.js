TLPA.ui.index = function()
{
	var bs = "";
	
	bs += "<h1>Three Level Password Authentication</h1>";
	bs+= "<br>";
	bs+= "<form class='form'>";
	bs+= "<input type='text' placeholder='Please enter your user name' id='in_user'/>";
	bs += "<button type='button' class='btn btn-success' id='send_login'>Login</button>";
	bs += "<button type='button' class='btn btn-success' id='send_register'>Register</button>";
	bs += "<br><br><a href='#recover'>Forgot password?</a>";
	bs+= "</form>";
	
	
	getContentManager().addToContent(bs);
};