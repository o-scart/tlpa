TLPA.ui.done = function(resp)
{
	var bs = "";
	
	bs += "<h1>Control Panel</h1><br>";
	bs += "<form class='form'>";
	bs += "<a href='#set_auth'><div id='navbutt' class='btn btn-success'>Change Passwords</div></a><br>";
	bs += "<a href='#set_recover'><div id='navbutt' class='btn btn-success'>Change Recovery Methods</div></a><br>";
	bs += "<button id='doauth' class='btn btn-primary'>Authenticate</button>";
	bs += "<button id='authlink' class='hidden btn btn-success'>Go to page now</button>";
	
	bs += "<a href='#loggedout'><div id='logoutbutt' class='btn btn-danger'>Logout</div></a><br>";
	
	
	bs += "</form>";
	
	bs += "<div id='login_graph'></div>";
	
	bs += "<div class='log'><b>User Login Statistics</b><table>";
	bs += "<thead><tr>";
	
	bs += "<th>Login</th><th>Logout</th><th>IP</th>";

	bs += "</tr></thead><tbody>";
	
	for(var i = 0; i < resp.log.length; i++)
	{
		bs += "<tr>";
		bs += "<td>" + resp.log[i].login + "</td>";
		bs += "<td>" + resp.log[i].logout + "</td>";
		bs += "<td>" + resp.log[i].ip + "</td>";
		bs += "</tr>";
	}
	bs += "</tbody>";	

	bs += "</table></div><br><br>";
	
	
	
	bs += "<div class='log'><b>User Bad Login Statistics</b><table>";
	bs += "<thead><tr>";
	
	bs += "<th>Time</th><th>IP</th>";
	bs += "</tr></thead><tbody>";
	
	for(var i = 0; i < resp.bad_log.length; i++)
	{
		bs += "<tr>";
		bs += "<td>" + resp.bad_log[i].event_time + "</td>";
		bs += "<td>" + resp.bad_log[i].ip + "</td>";
		bs += "</tr>";
	}
	bs += "</tbody>";
	bs += "</table></div>";
	
	var dates = [];
	
	for(var i = 0; i < resp.log.length; i++)
	{
		var date = resp.log[i].login.split(" ")[0];
		dates.push(date);
	}
	
	for(var i = 0; i < resp.bad_log.length; i++)
	{
		var date = resp.bad_log[i].event_time.split(" ")[0];
		dates.push(date);
		
	}
	
	dates = $.unique(dates);
	
	var logins = [];
	var failures = [];
	
	for(var i = 0; i < dates.length; i++)
	{
		logins.push($.grep(resp.log, function(e, index)
		{
			return e.login.indexOf(dates[i]) > -1;
		}).length);
		
		failures.push($.grep(resp.bad_log, function(e, index)
		{
			return e.event_time.indexOf(dates[i]) > -1;
		}).length);
	}
	
	bs += "";
	getContentManager().addToContent(bs);
	
	
	var chart = c3.generate({
		axis : {
			x : 
			{
				type : 'timeseries',
				tick: 
					{
						format: '%Y-%m-%d'
					}
				}
			},
		bindto: '#login_graph',
		
		data: {
			x: 'x',
		  columns: [
			
		  ],
		  type: 'bar'
		}
	});
	
	chart.load({columns: [
		['x'].concat(dates),
		['login'].concat(logins),
        ['login failures'].concat(failures)]});
	
};