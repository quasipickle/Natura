$(function(){
	$(".only_integer").keydown(function(e){
		return onlyNumbers(e.keyCode)
	});
	
	$(".only_decimal").keydown(function(e){
		if(onlyNumbers(e.keyCode) ||
		   (e.keyCode == 190 || e.keyCode == 110))//keyboard & numpad decimal
			return true;
		else
			return false;
	});
		   
});


function onlyNumbers(keyCode)
{
	if(	(keyCode >= 48 && keyCode <= 57) || //number row
		    (keyCode >= 96 && keyCode <= 105) || //keypad
		    keyCode == 8 || //backspace
		    keyCode == 46 || //delete
		    (keyCode >= 37 && keyCode <= 40) || //arrows
		    keyCode == 9 ) //tab
		return true;
	else
		return false;
}