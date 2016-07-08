TLPA.requests.get_session = function(user, callback)
{
	tlpareq('get_session', {
		user: user
	}, callback);
};