TLPA.ui.setrec_1 = function()
{
	var bs = "";
	
	bs += "<h1>Change Security Question</h1><br>";
	bs += "<form class='form'>";
	bs += "Security Question:<br><br> <input type='text' placeholder='Please enter a new question' id='in_quest' value=''/>";
	bs += "Answer:<br><br> <input type='text' placeholder='Please enter a new answer' id='in_answ' value=''/>";
	bs += "<button type='button' class='btn btn-success' id='send_quest'>Send</button>";
	bs += "<a href='#set_recover#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "";
	
	getContentManager().addToContent(bs);
};