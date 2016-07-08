TLPA.requests.auth = function(sessionId, sessionSecret, authSecret, callback)
{
	tlpareq('auth', {
		sessionId: sessionId,
		sessionSecret: sessionSecret,
		authSecret: authSecret
	}, callback);
};