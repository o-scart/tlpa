HourGlass = function () 
{

};

HourGlass.prototype.hide = function()
{
	$(".loadingarea").css("display", "none");
};

HourGlass.prototype.show = function()
{
	$(".loadingarea").css("display", "block");
};

function getHourGlass()
{
	return new HourGlass();
}