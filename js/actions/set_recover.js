TLPA.actions.set_recoverAction = function(extra)
{
	if(typeof sessionStorage.isAuthed === "undefined" || sessionStorage.isAuthed == false)
	{
		window.location = "#auth";
	}
	
	if(extra == "")
	{
		TLPA.ui.setrec_index();
	}
	if(extra == "0")
	{
		TLPA.ui.setrec_0();
		
		$("#send_mail").click(function()
		{
			TLPA.requests.set_recover(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, function(resp)
			{
				if(resp.status == "0")
				{
					window.location = "#set_recover#";
				}
			}, $("#in_mail").val()
			);
		});
	}
	if(extra == "1")
	{
		TLPA.ui.setrec_1();
		
		$("#send_quest").click(function()
		{
			TLPA.requests.set_recover(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, function(resp)
			{
				if(resp.status == "0")
				{
					window.location = "#set_recover#";
				}
			}, null, TLPA.prehash($("#in_answ").val()), $("#in_quest").val()
			);
		});
	}
	if(extra == "2")
	{
		TLPA.ui.setrec_2();
		
		$("#send_regenKF").click(function()
		{
			TLPA.requests.set_recover(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, function(resp)
			{
				if(resp.status == "0")
				{
					window.location = "#set_recover#";
				}
			}, null, null, null
			);
		});
	}
	getHourGlass().hide();
};