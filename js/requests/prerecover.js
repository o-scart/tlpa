TLPA.requests.prerecover = function(sessionId, sessionSecret, callback)
{
	tlpareq('prerecover', {
		sessionId: sessionId,
		sessionSecret: sessionSecret
	}, callback);
};