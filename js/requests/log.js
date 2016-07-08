TLPA.requests.log = function(sessionId, sessionSecret, callback)
{
	tlpareq('log', {
		sessionId: sessionId,
		sessionSecret: sessionSecret
	}, callback);
};