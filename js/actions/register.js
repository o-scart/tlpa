TLPA.actions.registerAction = function(extra)
{
	TLPA.ui.registerform(decodeURIComponent(extra));
	
	$("#send_register").click(function()
	{
		TLPA.requests.register($("#in_user").val(), 
		$("#in_mail").val(), 
		$("#sec_quest").val(), 
		TLPA.prehash($("#sec_answ").val()), 
		function(resp)
		{
			if(resp.status == 0)
			{
				sessionStorage.sessionId = resp.sessionId;
				sessionStorage.sessionSecret = resp.sessionSecret;				  
				  
				sessionStorage.firstTimeIn = true;
				sessionStorage.isAuthed = true;
				
				window.location = "#set_auth#0";
			}
			if(resp.status == 7)
			{
				alert("username already in use!");
			}
			
		});
	});
	getHourGlass().hide();
};