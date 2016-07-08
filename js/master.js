window.onload = function()
{
	TLPA.actions.init();
	
	for(var i = 0; i < inters.length; i++)
	{
		if(inters[i] != null)
		{
			clearInterval(inters[i]);
		}
	}
	inters = [];
	
	if(timeoutCounter == null)
	{
		timeoutCounter = setInterval(function()
		{
			if(isNaN(sessionStorage.timeout))
			{
				$("#timeoutwatch").html("");
				return;
			}
			var d = new Date();
			var seconds = Math.floor(d.getTime() / 1000);
			
			var timeTo = sessionStorage.timeout - seconds;
			
			if(timeTo == 0)
			{
				delete sessionStorage.timeout;
				return;
			}
			$("#timeoutwatch").html("Session expires in " + Math.floor(timeTo/60) + ":" + (timeTo%60)+" minutes");
		}, 1000)
	}
};

var timeoutCounter = null;

var inters = [];

var ajax = null;

$(window).on('hashchange', function() 
{
	$("#main").html("");
	if(ajax != null)
	{
		ajax.abort();
	}
	window.onload();
});