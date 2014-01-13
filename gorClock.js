/**
 * Gorean Clock
 * by Travis Veazey
 *
 * This script takes the user's current time and converts it into Gorean.
 */

//Container for our Gorean clock
var gorClock;
//Optional container for an Earth clock
var earthClock;

//Define the numbers of units in various subdivisions
var ahnPerDay = 20;
var ehnPerAhn = 40;
var ihnPerEhn = 80;

//The following are conveniences so we don't have to repeat these calculations
var ihnPerDay = ahnPerDay * ehnPerAhn * ihnPerEhn;
var secondsPerDay = 60 * 60 * 24;

/**
 * startGorClock
 * This function should be called after the DOM has finished loading, and will
 * initiate a timer to update the clock(s).
 */
function startGorClock()
{
	//Populate our containers
	gorClock = document.getElementById('gorclock');
	earthClock = document.getElementById('earthclock');

	//Update our clock(s)
	updateGorClock();
	//Set the interval to update the clock(s) periodically
	setInterval(function(){updateGorClock()}, 200);
}

/**
 * updateGorClock
 * This function should be called periodically to update the Gorean clock. Since
 * a Gorean Ihn (second) is 1.35 seconds, it needs to be called frequently enough
 * to keep the updates smooth. startGorClock() handles this with 5 updates per
 * (Earth) second.
 */
function updateGorClock()
{
	date = new Date();

	//If we have an Earth clock, update that
	if(earthClock)
	{
		earthClock.innerHTML = pad(date.getHours())+':'+pad(date.getMinutes())+':'+pad(date.getSeconds());
	}

	//Calculate seconds elapsed today
	daySeconds = date.getSeconds() + date.getMinutes() * 60 + date.getHours() * 3600;
	daySeconds += date.getMilliseconds() / 1000;
	//Convert to Ihn
	ihn = Math.round(daySeconds / secondsPerDay * ihnPerDay);

	//Find the current Ahn, and the remaining Ihn
	ahn = Math.floor(ihn / (ihnPerEhn * ehnPerAhn));
	ihn -= ahn * ehnPerAhn * ihnPerEhn;
	ahn += 1; //Ahns start at 1st, not 0

	//Find the current Ehn, and the remaining Ihn
	ehn = Math.floor(ihn / ihnPerEhn);
	ihn -= ehn * ihnPerEhn;

	//Now update the clock
	gorClock.innerHTML = pad(ahn)+':'+pad(ehn)+':'+pad(ihn);
}

/**
 * pad
 * Helper function to pad single-digit time parts to give them a leading 0.
 */
function pad(n)
{
	if(10 > n)
	{
		return '0'+n;
	} else {
		return ''+n;
	}
}

