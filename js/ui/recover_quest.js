TLPA.ui.recover_quest = function(quest)
{
	var bs = "";
	bs += "<h1>Recovery: Security Question</h1><br>";
	bs += "<form class='form'>"+ quest;
	bs += "<div class='rec'>";
	bs += "<br><br> <input type='text' placeholder='Please enter your answer here' id='in_answer' value='" + "" + "'/>";
	bs += "</div>";
	bs += "<button type='button' class='btn btn-success' id='send_recover'>Send</button>";
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "<div class='reg'>";
	bs += "";
	
	getContentManager().addToContent(bs);
};