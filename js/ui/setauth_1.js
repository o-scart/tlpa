TLPA.ui.setauth_1 = function(isLogin)
{
	var bs = "";
	bs+= "<h1 align=center>Stage 2: Color Pattern</h1>";
	bs+= "<br><br>";
	bs+= "<form class='form'>Please enter your color pattern sequence";
	bs+= "<div id='in_pattern'></div>";
	bs+= "<br><br>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-1' seqdat='1' id='c_1'>red</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-2' seqdat='2' id='c_2'>yellow</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-3' seqdat='3' id='c_3'>blue</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-4' seqdat='4' id='c_4'>green</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-5' seqdat='5' id='c_5'>orange</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-6' seqdat='6' id='c_6'>purple</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-7' seqdat='7' id='c_7'>brown</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-8' seqdat='8' id='c_8'>grey</button></a>";
	bs+= "<a><button type='button' class='btn seqbtn btn-sq-9' seqdat='9' id='c_9'>black</button></a>";
	bs+= "<br><br>";
	bs+= "<button type='button' class='btn btn-success' id='send_pw'>Send</button>";
	bs+= "<button type='button' class='btn btn-warning' id='clear_pw'>Clear input</button>";
	if(isLogin)
	{
		bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	}
	else
	{
		bs += "<a href='#set_auth#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	}
	bs+= "</form>";
	bs+= "";
	
	getContentManager().addToContent(bs);
};