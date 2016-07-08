TLPA.ui.recover_kf = function()
{
	var bs = "";
	
	bs += "<h1>" + "Recovery: Upload Keyfile" + "</h1><br>";
	bs += "<form class='form'>";
	bs += "<br>Please upload the Keyfile that was sent to you when you registered";
	bs += "<div class='rec'>";
	bs += "<input type='file' name='submitfile' id='submitfile' />";
	bs += "</div>";
	bs += "<button type='button' class='btn btn-success' id='send_recover'>Send</button>";
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "<div class='reg'>";
	bs += "";
	
	getContentManager().addToContent(bs);
};