
function gorDate(obj)
{
	if('string' == typeof obj)
	{
		obj = document.getElementById(obj);
	}

	var now = new Date();
	var days = getDaysYearToDate(now);
	var equinox = getDaysYearToDate(new Date(now.getFullYear(), 2, 20));
	//alert(now);
	alert(days);
	alert(equinox);
	//alert(new Date(now.getFullYear(), 2, 20));

	var year = now.getFullYear();
	if(days < equinox)
	{
		year--;
	}
	var daysInYear = getIsLeapYear(year+1)?366:365;

	days -= equinox;
	if(0 > days)
	{
		days += daysInYear;
	}

	var month = Math.floor(days/30);
	var week = Math.floor((days - month*30)/5);
	var day = days % 5;

	obj.innerHTML = day + ' of ' + week + ' of ' + month;
}

function getDaysYearToDate(now)
{
	if(null == now)
	{
		now = new Date();
	}
	var start = new Date(now.getFullYear(), 0, 1);
	var diff = now - start;
	alert(now);
	alert(start);
	alert(diff);

	return Math.floor(diff / (1000 * 60 * 60 * 24));
}

function getIsLeapYear(year)
{
	return ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0);
}
