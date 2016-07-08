TLPA.actions.recoverAction = function(extra)
{
	if(sessionStorage.sessionId == null)
	{
		TLPA.ui.recover_username();
		
		$("#send_recover").click(function()
		{
			TLPA.requests.get_session($("#in_user").val(), function(resp)
			{
				if(resp.status == '2')
				{
					alert("Nutzer nicht gefunden!");
					return;
				}
				if(resp.status == '0')
				{
					sessionStorage.sessionId = resp.sessionId;
					sessionStorage.sessionSecret = resp.sessionSecret;
					location.reload();
				}
			});
		});
		getHourGlass().hide();
		return;
	}
	
	TLPA.requests.prerecover(sessionStorage.sessionId, sessionStorage.sessionSecret, function(resp)
	{
		var stageKnowledge = resp.stageKnowledge;
		
		if(resp.recStage == 0)//secQuestion
		{
			TLPA.ui.recover_quest(stageKnowledge);
			$("#send_recover").click(function()
			{
				TLPA.requests.recover(sessionStorage.sessionId, sessionStorage.sessionSecret, TLPA.prehash($("#in_answer").val()), function(resp)
				{
					if(resp.status == "0")
					{
						window.location = window.location + "#";
					}
				});
			});
		}
		
		if(resp.recStage == 1)//keyfile
		{
			TLPA.ui.recover_kf(stageKnowledge);
			
			var kf;
			
			$('#submitfile').change(function()
			{
				var imgFile = document.getElementById('submitfile');
				if (imgFile.files && imgFile.files[0]) 
				{
					var reader = new FileReader();
					reader.onload = function(event) 
					{
						var dataUri = event.target.result;

						kf = dataUri;
						
				   };
				   reader.onerror = function(event) {
					   console.error("File could not be read! Code " + event.target.error.code);
				   };
				   reader.readAsDataURL(imgFile.files[0]);
				}
			});
			
			$("#send_recover").click(function()
			{
				TLPA.requests.recover(sessionStorage.sessionId, sessionStorage.sessionSecret, kf, function(resp)
				{
					if(resp.status == "0")
					{
						window.location = window.location + "#";
					}
				});
			});
		}
		
		if(resp.recStage == 2)//OTP
		{
			var timeout = stageKnowledge;
			TLPA.ui.recover_otp(timeout);
			inters.push(setInterval(function()
			{
				var d = new Date();
				var seconds = Math.floor(d.getTime() / 1000);
				
				var timeTo = timeout - seconds;
				
				if(timeTo == 0)
				{
					return;
				}
				$("#TO_min").html(Math.floor(timeTo/60));
				$("#TO_sec").html(timeTo%60);
			}, 1000));
			
			$("#send_recover").click(function()
			{
				TLPA.requests.recover(sessionStorage.sessionId, sessionStorage.sessionSecret, $("#in_otp").val(), function(resp)
				{
					if(resp.status == "0")
					{
						window.location = window.location + "#";
					}
				});
			});
			
		}
		
		if(resp.recStage == 3)//end
		{
			window.location = "#done";
		}
		
	});
	
	
	
};