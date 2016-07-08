TLPA.requests.set_auth = function(sessionId, sessionSecret, authStage, authSecret, callback)
{
	tlpareq('set_auth', {
		sessionId: sessionId,
		sessionSecret: sessionSecret,
		authStage: authStage,
		authSecret: authSecret
	}, callback);
};