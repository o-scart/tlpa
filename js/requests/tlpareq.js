function tlpareq(action, data, callback)
{
	
	getHourGlass().show();
	$.ajax({url: "xhr/" + action + ".php", 
	type: "POST",
	data : data,
	success: function(result)
	{
		console.log(result);
		var result = JSON.parse(result);
		
		if(result.timeout != null)
		{
			sessionStorage.timeout = result.timeout;
		}
		
		if(result.status == '6')
		{
			delete sessionStorage.sessionId;
			delete sessionStorage.sessionSecret;
			window.location = "#loggedout";
		}
		
		if(result.status == '8')
		{
			location.reload();
		}
		
		if(result.status == 5)
		{
			alert("incorrect secret entered; you will be logged out");
			window.location = "#loggedout#";
		}
		
		if(result.status == 3)
		{
			alert("your account was temporarily locked; please unlock it with the link you'll find in your mail inbox");
			return;
		}
		
		if(result.status == 4)
		{
			alert("your account was permanently locked; please contact the administrator");
			return;
		}
		
        callback(result);
		
		getHourGlass().hide();
    }});
}