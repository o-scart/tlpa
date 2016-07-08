TLPA.ui.setauth_2 = function()
{
	var bs = "";

	bs += "<h1 align=center>Stage 3: Image Puzzle</h1><br><br>";
	bs += "";
	bs += "<div class='cropper-menu cropper-cancel btn btn-danger'>cancel</div>";
	bs += "<div id='imgdiv'></div>";
	bs += "<div class='cropper-menu cropper-ready btn btn-success'>ready</div>";
	bs += "<div id='imgdiv'></div>";
	bs += "<div align='center'><canvas id='canvas'></canvas></div><br><br>";
	bs += "<div align='center'><input class='btn btn-default btn-file' type='file' name='submitfile' id='submitfile'/></div><br><br>";
	bs += "<br>";
	bs += "";
	bs += "<form class='form'>Please upload an image, select the area you want to save and press the bottom right 'ready'-button.<br><br><button type='button' class='btn btn-success' id='btload'>Upload</button><br>";
	bs += "<a href='#set_auth#'><button type='button' class='btn btn-danger' id='abort'>Abort</button></a>";
	bs += "</form>";
	getContentManager().addToContent(bs);
};