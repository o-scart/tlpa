TLPA.requests.end_session = function(sessionId, sessionSecret, callback)
{
	tlpareq('end_session', {
		sessionId : sessionId,
		sessionSecret : sessionSecret
	}, callback);
};