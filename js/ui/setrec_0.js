TLPA.ui.setrec_0 = function()
{
	var bs = "";
	
	bs += "<h1>Change email adress</h1><br>";
	bs += "<form class='form'>";
	bs += "Email adress:<br><br> <input type='text' placeholder='Please enter a new email adress' id='in_mail' value=''/>";
	bs += "<button type='button' class='btn btn-success' id='send_mail'>Send</button>";
	bs += "<a href='#set_recover#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "";
	
	getContentManager().addToContent(bs);
};