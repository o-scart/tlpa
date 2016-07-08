TLPA.actions.loggedoutAction = function(extra)
{
	TLPA.requests.end_session(sessionStorage.sessionId, sessionStorage.sessionSecret, function(resp)
	{
		delete sessionStorage.sessionId;
		delete sessionStorage.sessionSecret;
		delete sessionStorage.timeout;
		delete sessionStorage.firstTimeIn;
		delete sessionStorage.isAuthed;
		TLPA.ui.loggedout();
		getHourGlass().hide();
	});
	
};