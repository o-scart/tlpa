TLPA.actions.authAction = function(extra)
{
	TLPA.requests.preauth(sessionStorage.sessionId, sessionStorage.sessionSecret, function(resp)
	{
		if(resp.status == 0)
		{
			if(resp.authStage > 2)
			{
				sessionStorage.isAuthed = true;
				window.location = "#done";
			}
			
			switch(Number(resp.authStage))
			{
				case 0:
					TLPA.ui.auth_pw();
					
					$("#send_pw").click(function()
					{
						TLPA.requests.auth(sessionStorage.sessionId, sessionStorage.sessionSecret, TLPA.prehash($("#in_pw").val()), function(resp)
						{
							if(resp.status == "0")
							{
								window.location = window.location + "#"
							}
						});
					});
				break;
				case 1:
					TLPA.ui.setauth_1(true);
		
					$("#clear_pw").click(function()
					{
						$("#in_pattern").html("");
					});
					
					$(".seqbtn").click(function()
					{
						$("#in_pattern").append("<div style='background: " + $(this).css("background-color") + ";' class='seqdatelem' seqdat='" + $(this).attr("seqdat") + "' >" + $(this).text() + "</div>");
					});
					
					$("#send_pw").click(function()
					{
						var dat = $("#in_pattern").find(".seqdatelem");
						var out = "";
						
						for(var i = 0; i < dat.length; i++)
						{
							out += "" + $(dat[i]).attr("seqdat");
						}
						console.log(out);
						out = TLPA.prehash(out);
						console.log(out);
						TLPA.requests.auth(sessionStorage.sessionId, sessionStorage.sessionSecret, out, function(resp)
						{
							if(resp.status == "0")
							{
								window.location = window.location + "#";
							}
						});
					});
				break;
				case 2:
					TLPA.ui.auth_grid(resp.stageKnowledge);
					
					$(".gridster ul").gridster({
						widget_margins: [5, 5],
						max_cols : 3,
						min_cols : 3,
						widget_base_dimensions: [200, 200]
					});
					
					$("#send_grid").click(function()
					{
						var out = [];
						
						for(var i = 1; i < 4; i++)
						{
							for(var j = 1; j < 4; j++)
							{
								var e = $("li[data-row='" + i + "'][data-col='" + j + "']");
								
								if(e.length != 1)
								{
									alert("Feld ist nicht 3x3!");
									return;
								}
								e = $(e[0]);
								out.push(e.attr("pic-hash"));
							}
						}
						
						console.log(out);
						
						TLPA.requests.auth(sessionStorage.sessionId, sessionStorage.sessionSecret, JSON.stringify(out), function(resp)
						{
							if(resp.status == "0")
							{
								window.location = window.location + "#";
							}
						});
					});
				break;
				default: 
				if((sessionStorage.firstTimeIn+"") == "true")
				{
					window.location = "#set_auth#0";
				}
				else
				{
					window.location = "#done";
				}
			}
		}
	});
	
};