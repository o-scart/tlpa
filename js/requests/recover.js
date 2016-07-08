TLPA.requests.recover = function(sessionId, sessionSecret, recSecret, callback)
{
	tlpareq('recover', {
		sessionId: sessionId,
		sessionSecret: sessionSecret,
		recSecret: recSecret
	}, callback);
};