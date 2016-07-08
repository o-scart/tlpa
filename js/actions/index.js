TLPA.actions.indexAction = function(extra)
{
	TLPA.ui.index();
	
	$("#send_login").click(function()
	{
		TLPA.requests.get_session($("#in_user").val(), function(resp)
		{
			console.log(resp);
			if(resp.status == '2')
			{
				alert("Nutzer nicht gefunden!");
				return;
			}
			if(resp.status == '0')
			{
				sessionStorage.sessionId = resp.sessionId;
				sessionStorage.sessionSecret = resp.sessionSecret;
				window.location = "#auth";
			}
		});
	});
	
	$("#send_register").click(function()
	{
		window.location = "#register#" + encodeURIComponent($("#in_user").val());
	});
	getHourGlass().hide();
};