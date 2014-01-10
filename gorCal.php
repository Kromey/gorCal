<?php
/**
 * gorCal
 * by Travis Veazey
 * This object can calculate the Gorean date and/or print a Gorean calendar.
 */
class gorCal
{
	protected $Date; //Current date, stored as day of the year
	protected $Equinox; //Date of the Vernal Equinox
	protected $Year; //Gregorian year at the start of the current Gorean year
	protected $LeapYear; //Whether or not the current year is a Leap Year

	//List of names for the (0-indexed) months
	protected $MonthNames = array(
		0 => 'En\'Kara',
		3 => 'En\'Var',
		6 => 'Se\'Kara',
		9 => 'Se\'Var'
	);

	/**
	 * __construct
	 * Constructor method that optionally takes a Gregorian date to be used as
	 * the basis of the new object; if no date is supplied, the current date is
	 * used.
	 * See PHP's strtotime() function for valid date formats; if translation
	 * fails the current date will be used instead.
	 *
	 * @param mixed $date (Optional) Gregorian date for this instance.
	 * @return void
	 */
	public function __construct($date = null)
	{
		if(false == is_null($date))
		{
			//Attempt to convert the date to a time stamp.
			$date = strtotime($date);
		}
		if(true == is_null($date) || $date === false || $date == -1)
		{
			//If no date was supplied, or strtotime() failed to translate it, use
			//the current date.
			$date = time();
		}

		//Get the year
		$this->Year = date('Y', $date);

		//NB: We use a hard-coded Vernal Equinox of 20 March
		$this->Equinox = date('z', strtotime('20 March '.$this->Year));

		//Now convert our date to the number of days since the Gorean New Year
		$this->Date = date('z', $date) - $this->Equinox;

		if(date('z', $date) < $this->Equinox)
		{
			//If we haven't reached the Vernal Equinox, it's "last
			//year" still on the Gorean calendar
			$this->Year--;
		}

		//Using the Gregorian method for calculating Leap Year, determine if it is
		//a Leap Year, BUT keep in mind that Leap Day is before the Equinox and
		//thus the Gregorian "next year"
		$year = $this->Year + 1;
		if((0 == $year % 4 && 0 != $year % 100) || 0 == $year % 400)
		{
			$this->LeapYear = true;
		} else {
			$this->LeapYear = false;
		}

		//Now normalize our Gorean days elapsed to start at 0
		if(0 > $this->Date)
		{
			$this->Date += 365 + ($this->LeapYear?1:0);
		}
	}

	/**
	 * printDate
	 * Prints the current Gorean date.
	 *
	 * @return void
	 */
	public function printDate()
	{
		echo ucfirst($this->getDay());
		$date[] = $this->getWeek();
		$date[] = $this->getMonth();
		$date[] = $this->getYear();

		//Not all dates have a week and month part
		//Only echo those we actually have
		foreach($date as $part)
		{
			if($part)
			{
				echo ' of '.$part;
			}
		}
	}

	/**
	 * getIsLeapYear
	 * Returns true if the current year is a Leap Year.
	 *
	 * @return bool Whether or not the current year is a Leap Year
	 */
	public function getIsLeapYear()
	{
		return $this->LeapYear;
	}

	/**
	 * getYear
	 * Returns the current Gorean year. If the optional parameter $formatted
	 * is ommitted, or supplied and true, the year is formatted with a
	 * thousands separator and the appropriate suffix (i.e. 'CA'); if it is
	 * set to false, the year is returned as a simple number.
	 * TODO: Allow year offset and suffix to be specified.
	 *
	 * @param bool $formatted (Optional) Set to false return an unformatted number
	 * @return mixed The current Gorean year
	 */
	public function getYear($formatted = true)
	{
		$year = $this->Year + 8150;

		if(true == $formatted)
		{
			return number_format($year) . ' CA';
		} else {
			return $year;
		}
	}

	/**
	 * getMonth
	 * Returns the current Gorean month. If the optional parameter $formatted
	 * is ommitted, or supplied and true, the month is formatted and, if
	 * available, returned with the defined proper name.
	 * A formatted month looks like: "the 3rd month" -or- "Se'Var"
	 * An unformatted month is simple the (0-indexed) month number.
	 * For Passage Hands, the unformatted month actually represents the previous
	 * month, while a formatted month is the empty string; for Waiting Hands and
	 * Leap Year, the unformatted month is returned as 12 (representing the "13th
	 * month"), however the formatted month is again the empty string.
	 *
	 * @param bool $formatted (Optional) Set to false return an unformatted number
	 * @return mixed The current Gorean month
	 */
	public function getMonth($formatted = true)
	{
		//There are 5 days in a week, and 4 weeks in a month
		//But there's also a Passing Hand after each month
		$month = floor($this->Date / 30);

		if(true == $formatted)
		{
			if(365 == $this->Date)
			{
				//Leap Year isn't part of any month
				return '';
			} elseif(5 == $this->getWeek(false)) {
				//There isn't a 6th week, but a Passage Hand between months
				return '';
			} elseif(12 == $month) {
				//The "13th month" is actually the Waiting Hand, not a month
				return '';
			} else {
				$name = $this->getMonthName($month);
				if(false == is_null($name))
				{
					return $name;
				} else {
					return 'the '.$this->ordSuffix($month+1).' month';
				}
			}
		} else {
			return $month;
		}
	}

	/**
	 * getWeek
	 * Returns the current Gorean week of the month. If the optional parameter
	 * $formatted is omitted, or supplied and true, the week is formatted; if it
	 * is false, returns simple a number representing the (0-indexed) week number
	 * of the current month.
	 * Passage Hand and Waiting Hand are returned as such when formatted; when
	 * unformatted, Passage Hands have the value 5 (6th week), while Waiting Hand
	 * has the value 0 (1st week). Leap Year is returned as the empty string when
	 * formatted, or 1 (2nd week) when unformatted.
	 *
	 * @param bool $formatted (Optional) Set to false return an unformatted number
	 * @return mixed The current Gorean week of the month
	 */
	public function getWeek($formatted = true)
	{
		$week = floor($this->Date / 5) % 6;

		if(true == $formatted)
		{
			if(365 == $this->Date)
			{
				//Leap Year isn't part of any week
				return '';
			} elseif(5 == $week) {
				//There isn't a 6th week, just a Passage Hand between months
				return 'the '.$this->ordSuffix($this->getMonth(false)+1).' Passage Hand';
			} elseif(12 == $this->getMonth(false)) {
				//There isn't a 13th month, but a Waiting Hand instead
				return 'the Waiting Hand';
			} else {
				return 'the '.$this->ordSuffix($week+1).' week';
			}
		} else {
			return $week;
		}
	}

	/**
	 * getDay
	 * Returns the current Gorean day of the week. If the optional parameter
	 * $formatted is omitted, or supplied and true, the day is formatted as
	 * e.g. "the 2nd day"; if it is false, returns a simple number representing
	 * the (0-indexed) day of the week.
	 * When formatted, Leap Year is returned as "Leap Year", or as 0 when not
	 * formatted.
	 *
	 * @param bool $formatted (Optional) Set to false return an unformatted number
	 * @return mixed The current Gorean day of the week
	 */
	public function getDay($formatted = true)
	{
		$day = $this->Date % 5;

		if(true == $formatted)
		{
			if(365 == $this->Date)
			{
				return 'Leap Year';
			} else {
				return 'the '.$this->ordSuffix($day+1).' day';
			}
		} else {
			return $day;
		}
	}

	/**
	 * getMonthName
	 * Given a (0-indexed) month number, this method either returns a string
	 * containing the name of the month (e.g. "Se'Var"), or NULL if no name is
	 * defined.
	 *
	 * @param integer $month The 0-indexed month
	 * @return mixed The month name, or NULL if no name defined
	 */
	public function getMonthName($month)
	{
		if(isset($this->MonthNames[$month]) && false == is_null($this->MonthNames[$month]))
		{
			return $this->MonthNames[$month];
		} else {
			return null;
		}
	}

	/**
	 * printCalendar
	 * Print a calendar for the current Gorean year.
	 * The parameter $width defines how many months should be displayed on each
	 * row of the calendar (default: 1); $years defines how many years calendars
	 * should be printed for, starting with the current year (default: 1). If
	 * $highlightDate is true (default), the current date is highlighted with the
	 * CSS class "currentDate".
	 *
	 * @param integer $width Number of months per row
	 * @param integer $years Number of years to print
	 * @param bool $highlightDate Whether or not to highlight the current date
	 * @return void
	 */
	public function printCalendar($width = 1, $years = 1, $highlightDate = true)
	{
		$YearDays = 365 + ($this->LeapYear?1:0);
		echo '<table class="goreanCalendar">';
		echo '<tr><td class="spacer"></td>';
		echo '<th colspan="'.(5*$width).'">'.$this->getYear().' <span class="earthYear">Earth: '.$this->Year.' AD</span></th></tr>';
		for($w = 0; $w < ceil($YearDays/5/$width); $w++)
		{
			if(0 == $w%6 && 72 > $w*$width)
			{
				echo '<tr><td class="spacer"></td>';
				for($m = 0; $m < $width; $m++)
				{
					$month = $m + floor($w / 6)*$width;
					if(11 < $month)
					{
						break;
					}
					$name = $this->getMonthName($month);
					if(is_null($name))
					{
						$name = $this->ordSuffix($month+1).' month';
					}
					echo '<th class="monthHeader" colspan="5">'.$name.'</th>';
				}
				echo '</tr>';
			}

			$week = $w % 6;
			$isMonth = ($week < 5);
			if(72 == $w*$width)
			{
				$weekLabel = '<th>Waiting Hand</th>';
				$isMonth = false;
			} elseif(72 < $w*$width) {
				echo '<tr class="handBorder">';
				$this->printLeapYear(true);
				echo '</tr>';
				break;
			} elseif(5 == $week) {
				$weekLabel = '<th>Passage Hand</th>';
			} else {
				$weekLabel = '<th>'.$this->ordSuffix($week+1).' week</th>';
			}
			if(!$isMonth)
			{
				echo '<tr class="handBorder">';
			} else {
				echo '<tr>';
			}
			echo $weekLabel;

			for($i = 0; $i < $width*5; $i++)
			{
				$d = $i % 5;
				$m = floor($i / 5) + floor($w / 6) * ($width-1);

				$day = $m * 30 + $w * 5 + $d;

				echo '<!-- '.$day.' of '.$YearDays.' -->';
				if($day >= $YearDays) {
					if($this->LeapYear)
					{
						$this->printLeapYear(false);
					}
					break;
				}

				echo '<td';
				$class = array();
				if(true == $highlightDate && $day == $this->Date)
				{
					$class[] = 'currentDate';
				}
				if(0 == $d)
				{
					$class[] = 'monthBorder';
				}
				if(0 < count($class))
				{
					echo ' class="'.implode(' ', $class).'"';
				}
				echo '>'.nl2br(date("M\nd", strtotime("20 March {$this->Year} +{$day} days"))).'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';

		if(1 < $years)
		{
			$cal = new gorCal("20 March ".($this->Year + 1));
			$cal->printCalendar($width, $years-1, false);
		}
	}

	/**
	 * ordSuffix
	 * A helper method that returns the supplied integer with the ordinal suffix
	 * appropriate for it in English, i.e. 1st, 2nd, 3rd, etc.
	 *
	 * @param integer $n The number
	 * @return string The number with its ordinal suffix attached
	 */
	protected function ordSuffix($n)
	{
		if(20 <= $n)
		{
			//For numbers above the teens, we care only for the last digit
			$d = $n % 10;
		} else {
			$d = $n;
		}

		switch($d)
		{
			case 1:
				$suffix = 'st';
				break;
			case 2:
				$suffix = 'nd';
				break;
			case 3:
				$suffix = 'rd';
				break;
			default:
				$suffix = 'th';
		}

		return $n.$suffix;
	}

	/**
	 * printLeapYear
	 * This helper method prints the table cells necessary to display Leap Year on
	 * the calendar, either as a separate row ($isSeparateRow true) or as part of
	 * the row with the Waiting Hand ($isSeparateRow false).
	 *
	 * @param bool $isSeparateRow Whether or not Leap Year is on its own row
	 * @return void
	 */
	protected function printLeapYear($isSeparateRow)
	{
		if($isSeparateRow)
		{
			echo '<th>';
		} else {
			echo '<th class="monthBorder" colspan="3">';
		}
		echo 'Leap Year</th>';
		echo '<td class="monthBorder">'.nl2br(date("M\nd", strtotime("19 March {$this->Year}"))).'</td>';
	}
}
