var TLPA =
{
	actions : 
	{
		init : function()
		{
			console.log("init");
			var action = "index";
			var extradata = "";
			var urlpaths = String(window.location).split( '#' );
			if(urlpaths.length > 1 && urlpaths[1] != "")
			{
				action = urlpaths[1];
			}
			if(urlpaths.length > 2 && urlpaths[2] != "")
			{
				extradata = urlpaths[2];
			}
			TLPA.actions[action + "Action"](extradata);
		}
	},
	ui : 
	{
		
	},
	requests : 
	{
		
	},
	prehash : function(pw)
	{
		return "" + CryptoJS.PBKDF2(pw+"", "766756§%%§UHgSrfgfgNNNfgfN/", { keySize: 512/32, iterations: 500, hasher:CryptoJS.algo.SHA512}).toString();
	}
};