TLPA.requests.register = function(user, mail, secquest, secansw, callback)
{
	tlpareq('register', {
		user: user,
		mail: mail,
		secQuestion: secquest,
		secAnswer: secansw
	}, callback);
};