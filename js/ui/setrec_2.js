TLPA.ui.setrec_2 = function()
{
	var bs = "";
	
	bs += "<h1>Regenerate Keyfile</h1><br>";
	bs += "<form class='form'>";
	bs += "<button type='button' class='btn btn-success' id='send_regenKF'>Regenerate Keyfile</button>";
	bs += "<a href='#set_recover#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "";
	
	getContentManager().addToContent(bs);
};