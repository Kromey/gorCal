<?php
ini_set('display_errors', '1');
include_once('gorCal.php');
?>
<html>
<head>
<title>Gorean Time/Date</title>
<script type="text/javascript" src="gorClock.js"></script>
<script type="text/javascript" src="gorDate.js"></script>
<link rel="stylesheet" href="gorCal.css" />
<style type="text/css">
body
{
	background-color: #B18904;
}

h1,h2
{
	text-align: center;
}

#gorclockcontainer,
#gordatecontainer
{
	text-align: center;
	color: #CCCCCC;
}

#gorclock,
#gordate
{
	font-size: 35px;
	color: #000000;
}

#earthclockcontainer,
#earthdatecontainer
{
	text-align: center;
	float: right;
	color: #CCCCCC;
	margin-top: -45px;
	margin-right: 50px;
}

#earthclock,
#earthdate
{
	color: #000000;
}

#clockexplained,
#dateexplained
{
	margin-right: 100px;
	margin-left: 100px;
}
</style>
</head>
<body onload="startGorClock();gorDate('gordatejs');">

<div id="time">
<h2>Your Local Time on Gor</h2>
<div id="gorclockcontainer">
	Ahn : Ehn : Ihn
	<div id="gorclock">--:--:--</div>
</div>
<div id="earthclockcontainer">
	Earth Time
	<div id="earthclock">--:--:--</div>
</div>
<div id="clockexplained">
One day on Gor lasts 20 Ahn; there are 40 Ehn in 1 Ahn; and there are 80 Ihn in
1 Ehn. This clock displays your current local time converted to Gorean, after
making the assumption that a 20-Ahn day on Gor is the same as a 24-hour day on
Earth. While there is room to argue whether or not this is true (I tend to
believe it's close enough to true), the goal is not to represent the time on Gor,
but rather merely to bring Gorean time to Earth and maintaining its relationship
to the day, rather than fixing the length of an Ihn and converting from that.
</div>
</div>

<div id="date">
<h2>Today's Date on Gor</h2>
<div id="gordatecontainer">
	<div id="gordate"><?php $cal = new gorCal(); $cal->printDate(); ?></div>
	<div id="gordatejs"></div>
</div>
<div id="earthdatecontainer">
	Earth Date
	<div id="earthdate"><?php echo date('j M Y'); ?></div>
</div>
<div id="dateexplained">
The Gorean calendar in use here is based on a fixed start date of March 20th.
Why that and not the 21st as many others use? Because the Gorean year is based
on the Vernal Equinox, which here in the Western Hemisphere of Earth occurs on
the 20th or 21st about 50/50 -- but, at the Prime Meridian, where the rest of
Earth's time and date is based from, it almost always occurs on the 20th.
Therefore, for consistency with established methods of maintaining the time and
date on Earth, I chose to use the Prime Meridian as my point of reference, which
also conveniently allows my calendars to smoothly continue from one to the next.
I also chose to use the Gregorian calendar's method for computing when the New
Year is to make the same calculation here, and include the Gorean New Year on
that calendar which includes the Gregorian date February 29th -- thus when a
New Year falls on 2016, it appears on the Gorean calendar that begins in 2015,
since that is the one that would include the Gregorian Leap Day.<br />
This calendar does not (yet) include any Gorean festivals, and the only month
names in use are those four that are most common: En'Kara, En'Var, Se'Kara, and
Se'Var. For the year, this calendar presents Contasta Ar, and converts the Earth
Gregorian year based on the extrapolations done
<a href="http://www.thegoreancave.com/tmm/years.php" target="_blank">here</a>;
those are not mine, and I have not independently attempted to verify them, but
the reasoning seems sound and the conclusion plausible.<br />
As with the time, no attempt has been made to try and present the current true
date on Gor; instead, I have taken the Gorean calendar and used its basis to
relate it to the date here on Counter-Gor, that is it is based on Earth's
Vernal Equinox, rather than trying to determine Gor's and translate that across
to this planet.
</div>
<?php
echo "<br />\n";
$cal->printCalendar(3, 4);

for($i = 1; $i < 0; $i++)
{
	$cal2 = new gorCal("1 March 2016 +{$i} days");
	$cal2->printDate();
	echo "<br />\n";
}
?>
</div>

</body>
</html>
