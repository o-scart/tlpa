TLPA.ui.loggedout = function(mail)
{
	var bs = "";
	
	bs += "<h1>Three Level Password Authentication</h1>";
	bs += "<br>";
	bs+= "<form class='form'>";
	bs+= "<div class='bye'>Session closed. You were logged out.<br>Come back soon.</div>";
	bs+= "<br><br><br>";
	bs+= "<a href='#'><button type='button' class='btn btn-success' id='send_index'>Back to Index</button></a>";
	bs+= "</form>";
	bs+= "";
	
	getContentManager().addToContent(bs);
};