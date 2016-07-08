TLPA.ui.auth_grid = function(pictures)
{
	var bs = "";
	
	bs += "<h1>Stage 3: Image Puzzle</h1><br>";
	bs += "<div class='outergrid'><div class='gridster'><ul>";
	
	for(var i = 0; i < pictures.length; i++)
	{
		bs += "<li pic-hash='" + CryptoJS.SHA256(pictures[i]) + "' data-row='" + (Math.floor(i / 3) + 1) + "' data-col='" + ((i % 3) + 1) + "' data-sizex='1' data-sizey='1'>";
		bs += "<img src='data:image/jpeg;base64," + pictures[i] + "' />";
		bs += "</li>";
	}
	
	bs += "</ul></div></div>";
	bs+= "<br><form class='form'>";
	bs+="Please put the image pieces into right order<br><br>";
	bs += "<button type='button' class='btn btn-success' id='send_grid'>Send</button>";
	
	bs += "<a href='#loggedout#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	
	getContentManager().addToContent(bs);
};