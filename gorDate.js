
function gorDate(obj)
{
	if('string' == typeof obj)
	{
		obj = document.getElementById(obj);
	}

	var now = new Date();
	var days = getDaysToDate(now);
	var equinox = getDaysToDate(new Date(now.getFullYear(), 2, 20));
	console.log('Today is '+days+' days since Jan 1');
	console.log('The Equinox is '+equinox+' days since Jan 1');

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

	if(12 == month)
	{
		//Waiting Hand, or Leap Year
		if(0 == week)
		{
			//Waiting Hand
			obj.innerHTML = 'The '+ordSuffix(day+1)+' day of the Waiting Hand';
		} else {
			//Leap Year
			obj.innerHTML = 'Leap Year';
		}
	} else if(5 == week) {
		//Passage Hand
		obj.innerHTML = 'The '+ordSuffix(day+1)+' day of the '+ordSuffix(month+1)+' Passage Hand';
	} else {
		obj.innerHTML = 'The '+ordSuffix(day+1)+' day of the '+ordSuffix(week+1)+' week of the '+ordSuffix(month+1)+' month';
	}
}

function getDaysToDate(now)
{
	if(null == now)
	{
		now = new Date();
	}
	var start = new Date(now.getFullYear(), 0, 1, 0, 0, 0);
	var end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0);
	var diff = end - start;
	console.log('There are '+diff+' microseconds between '+start+' and '+end);

	return Math.round(diff / (1000 * 60 * 60 * 24));
}

function getIsLeapYear(year)
{
	return ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0);
}

function ordSuffix(n)
{
	if(20 <= n)
	{
		//For numbers above the teens, we care only for the last digit
		d = n % 10;
	} else {
		d = n;
	}

	switch(d)
	{
		case 1:
			suffix = 'st';
			break;
		case 2:
			suffix = 'nd';
			break;
		case 3:
			suffix = 'rd';
			break;
		default:
			suffix = 'th';
	}

	return n+suffix;
}
