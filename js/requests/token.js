TLPA.requests.token = function(sessionId, sessionSecret, callback)
{
	tlpareq('token', {
		sessionId: sessionId,
		sessionSecret: sessionSecret
	}, callback);
};