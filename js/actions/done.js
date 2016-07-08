TLPA.actions.doneAction = function(extra)
{
	if(typeof sessionStorage.isAuthed === "undefined" || sessionStorage.isAuthed == false)
	{
		window.location = "#auth";
	}
	
	TLPA.requests.log(sessionStorage.sessionId, sessionStorage.sessionSecret, function(resp)
	{
		TLPA.ui.done(resp);
		
		$("#doauth").click(function()
		{
			TLPA.requests.token(sessionStorage.sessionId, sessionStorage.sessionSecret, function(resp)
			{
				$("#authlink").attr("popup-target", resp.otp_auth);
				$("#authlink").removeClass("hidden");
			});
		});
		
		$("#authlink").click(function()
		{
			$(this).addClass("hidden");
			window.open($(this).attr("popup-target"));
		});
		
	});
	
	
	
};