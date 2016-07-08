TLPA.requests.set_recover = function(sessionId, sessionSecret, recStage, callback, probMail, probSecA, probSecQ)
{
	tlpareq('set_recover', {
		sessionId: sessionId,
		sessionSecret: sessionSecret,
		setRecovery: recStage,
		mail: probMail,
		secAnswer: probSecA,
		secQuestion: probSecQ
	}, callback);
};