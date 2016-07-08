TLPA.requests.unlock = function(sessionId, sessionSecret, token, callback)
{
	tlpareq('unlock', {
		sessionId: sessionId,
		sessionSecret: sessionSecret,
		token: token
	}, callback);
};