gorCal
======

Time and date functions for converting Earth times and dates to Gorean.

Gorean Time
-----------

gorCal provides a JavaScript library for the display of a real-time Gorean clock
that converts the user's current time to Gorean.

One day on Gor lasts 20 Ahn; there are 40 Ehn in 1 Ahn; and there are 80 Ihn in
1 Ehn. This clock displays your current local time converted to Gorean, after
making the assumption that a 20-Ahn day on Gor is the same as a 24-hour day on
Earth. While there is room to argue whether or not this is true (I tend to
believe it's close enough to true), the goal is not to represent the time on Gor,
but rather merely to bring Gorean time to Earth and maintaining its relationship
to the day, rather than fixing the length of an Ihn and converting from that.

To use the Gorean time JavaScript, simply include a block element on your page
with the HTML ID "gorclock", and then call the function startGorClock() after
the DOM is loaded (the onLoad even of the body tag is a good choice).

Gorean Date
-----------

Because it's much more complex than a simple clock, gorCal's date and calendar
support exist as a PHP class. While the hope is to one day provide this also as
a JavaScript object, at present it must be calculated server-side.

Basic usage involves instantiating a new gorCal object, and then calling the
'printDate()' method; calling 'printCalendar()' will print the calendar for the
current Gorean year, highlighting the current date. You can get Gorean dates or
calendars for other dates by simply passing the Gregorian date into the object,
e.g.
```php
$date = new gorCal('23 January 2013');
$date->printDate();
```

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
since that is the one that would include the Gregorian Leap Day.

This calendar does not (yet) include any Gorean festivals, and the only month
names in use are those four that are most common: En'Kara, En'Var, Se'Kara, and
Se'Var. For the year, this calendar presents Contasta Ar, and converts the Earth
Gregorian year based on the extrapolations done
[here](http://www.thegoreancave.com/tmm/years.php); those are not mine, and I
have not independently attempted to verify them, but the reasoning seems sound
and the conclusion plausible.

As with the time, no attempt has been made to try and present the current true
date on Gor; instead, I have taken the Gorean calendar and used its basis to
relate it to the date here on Counter-Gor, that is it is based on Earth's
Vernal Equinox, rather than trying to determine Gor's and translate that across
to this planet.
