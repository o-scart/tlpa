TLPA.ui.recover_otp = function(timeout)
{
	var bs = "";
	
	var d = new Date();
	var seconds = Math.floor(d.getTime() / 1000);
	
	bs += "<h1>" + "Recovery: One-time password" + "</h1><br>";
	bs += "<form class='form'>";
	bs += "<div class='rec'>";
	bs += "Time is running, Gordon Freeman: <br><br><div class='time'><div id='TO_min'>" + (timeout-seconds) + "</div> Minutes <div id='TO_sec'>" + (timeout%60) + " </div> Seconds</div>";
	bs += "<br><br>One-Time Password:<br><br> <input type='text' placeholder='Please enter the one-time password here' id='in_otp' value='" + "" + "'/>";
	bs += "</div>";
	bs += "<button type='button' class='btn btn-success' id='send_recover'>Send</button>";
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "<div class='reg'>";
	bs += "";
	
	getContentManager().addToContent(bs);
};