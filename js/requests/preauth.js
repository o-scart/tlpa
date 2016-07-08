TLPA.requests.preauth = function(sessionId, sessionSecret, callback)
{
	tlpareq('preauth', {
		sessionId: sessionId,
		sessionSecret: sessionSecret
	}, callback);
};