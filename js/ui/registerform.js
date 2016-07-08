TLPA.ui.registerform = function(user)
{
	var bs = "";
	
	bs += "<h1>Registration</h1><br>";
	bs += "<form class='form'>";
	bs += "<div class='reg'>";
	bs += "User name:<br><br> <input type='text' id='in_user' placeholder='Please enter a user name' value='" + user.replace(/[^a-zA-Z0-9\-]/g,'_') + "'/>";
	bs += "Email adress:<br><br> <input type='text' placeholder='Please enter your email adress' id='in_mail' value=''/>";
	bs += "Choose a safe security question you can easily remember:<br><br>";
	bs += "<input type='text' placeholder='Enter the question here' id='sec_quest' value=''/><br>";
	bs += "<input type='text' placeholder='Enter the correct answer here' id='sec_answ' value=''/>";
	bs += "</div>";
	bs += "<button type='button' class='btn btn-success' id='send_register'>Register</button>";
	bs += "<a href='#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	bs += "<div class='reg'>";
	bs += "";
	
	getContentManager().addToContent(bs);
};