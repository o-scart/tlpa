TLPA.actions.unlockAction = function(extra)
{
	var urlpaths = String(window.location).split( '#' );
		TLPA.requests.unlock(urlpaths[2], urlpaths[3], urlpaths[4], 
		function(resp)
		{
			if(resp.status != 0)
			{
				alert("Fehler beim entsperren!");
			}
			window.location = "#";
		});
};