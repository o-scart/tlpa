TLPA.actions.set_authAction = function(extra)
{
	if(typeof sessionStorage.isAuthed === "undefined" || sessionStorage.isAuthed == false)
	{
		window.location = "#auth";
	}
	
	if(extra == "")
	{
		TLPA.ui.setauth_index();
	}
	if(extra == "0")
	{
		TLPA.ui.setauth_0();
		
		$("#send_pw").click(function()
		{
			TLPA.requests.set_auth(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, TLPA.prehash($("#in_pw").val()), function(resp)
			{
				if(resp.status == "0")
				{
					if((sessionStorage.firstTimeIn+"") == "true")
					{
						window.location = "#set_auth#1";
					}
					else
					{
						window.location = "#set_auth#";
					}
				}
			});
		});
	}
	if(extra == "1")
	{
		TLPA.ui.setauth_1(false);
		
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

			out = TLPA.prehash(out);
			
			TLPA.requests.set_auth(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, out, function(resp)
			{
				if(resp.status == "0")
				{
					console.log(sessionStorage.firstTimeIn);
					if((sessionStorage.firstTimeIn+"") == "true")
					{
						window.location = "#set_auth#2";
					}
					else
					{
						window.location = "#set_auth#";
					}
				}
			});
		});
	}
	if(extra == "2")
	{
		TLPA.ui.setauth_2();
		var $canvas = $('#canvas');
		var canvas = $canvas[0];
		$('#btload').click(function()
		{
			TLPA.requests.set_auth(sessionStorage.sessionId, sessionStorage.sessionSecret, extra, canvas.toDataURL("image/jpeg", 90), function(resp)
			{
				if(resp.status == "0")
				{
					window.location = "#set_auth#";
				}
			});
		});
		
		$(".cropper-ready").click(function()
		{
			$("#imgdiv").html("");
			$(".cropper-menu").css("display", "none");
		});
		
		$(".cropper-cancel").click(function()
		{
			$(".cropper-ready").click();
			//throw away canvas
		});
		
		var maxWidth = 900;
		var maxHeight = 900;
		$('#submitfile').change(function()
		{
			var imgFile = document.getElementById('submitfile');
			if (imgFile.files && imgFile.files[0]) 
			{
				var width;
				var height;
				var fileSize;
				var reader = new FileReader();
				reader.onload = function(event) 
				{
					$("#imgdiv").html("");
					$('#imgdiv').prepend("<img id='image' />");
					var dataUri = event.target.result,
					img = document.getElementById("image");
					img.src = dataUri;
					width = img.width;
					height = img.height;	
					
					var options = 
					{
					  aspectRatio: 1,
					  crop: function (e) 
					  {
						newCanvasHeight = 600;//e.height <= maxHeight ? e.height : maxHeight;
						newCanvasWidth = 600;//e.width <= maxWidth ? e.width : maxWidth;
						canvas.height = newCanvasHeight;
						canvas.width = newCanvasWidth;
						var ctx = canvas.getContext('2d');
						ctx.drawImage(
						  img,
						  e.x, e.y, e.width, e.height,
						  0, 0, newCanvasWidth, newCanvasHeight
						);
					  }
					};
					
					var w = window.innerWidth
						|| document.documentElement.clientWidth
						|| document.body.clientWidth;

						var h = window.innerHeight
						|| document.documentElement.clientHeight
						|| document.body.clientHeight;
					
					$("#image").cropper(options);
					$("#image").css("maxWidth", "100vw");
					$("#image").css("maxHeight", "calc(100vh - 20px)");
					$(".cropper-container").css("width", w + "px");
					$(".cropper-container").css("height", h + "px");
					$(".cropper-menu").css("display", "block");
					
					
			   };
			   reader.onerror = function(event) {
				   console.error("File could not be read! Code " + event.target.error.code);
			   };
			   reader.readAsDataURL(imgFile.files[0]);
			}
		});
	}
	getHourGlass().hide();
};