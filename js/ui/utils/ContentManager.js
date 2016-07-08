ContentManager = function () 
{

};

ContentManager.prototype.addToContent = function(newHtml)
{
	this.addToId("main", newHtml);
};

ContentManager.prototype.addToId = function(id, newHtml)
{
	document.getElementById(id).innerHTML += newHtml;
	doStyle();
};

function getContentManager()
{
	return new ContentManager();
}

