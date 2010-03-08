<?php
	class OurFutureEU_ActivityCalendarModule {
		public static $tplVars = array();
		public static $tplFile = true;

		public static function run () {
			# Get the year/month the user is browsing
			if (isset(Router::$params['year']) and isset(Router::$params['month'])) {
				$year	= Router::$params['year'];
				$month	= Router::$params['month'];
			}
			# Or the current year month
			else {
				$year	= date('Y');
				$month	= date('m');
			}

			$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

			# Store previous month
			$previousMonth = mktime(0, 0, 0, $month - 1, 1, $year);
			$previousMonth = array(
				'ts'	=> $previousMonth, 
				'month'	=> date('m', $previousMonth), 
				'year'	=> date('Y', $previousMonth)
			);

			# And next month
			$nextMonth = mktime(0, 0, 0, $month + 1, 1, $year);
			$nextMonth = array(
				'ts'	=> $nextMonth, 
				'month'	=> date('m', $nextMonth), 
				'year'	=> date('Y', $nextMonth)
			);

			# Assign to template
			self::$tplVars = array(
				'selected_month'	=> array(
					'url'	=> "#$year-$month", # Router::urlFor('activitiesByMonth', array('year' => $year, 'month' => $month)), 
					'title'	=> date('F Y', $firstDayOfMonth)
				), 
				'previous_month'	=> array(
					'url'	=> "#{$previousMonth['year']}-{$previousMonth['month']}", # Router::urlFor('activitiesByMonth', array('year' => $previousMonth['year'], 'month' => $previousMonth['month'])), 
					'title'	=> date('F Y', $previousMonth['ts'])
				), 
				'next_month'	=> array(
					'url'	=> "#{$nextMonth['year']}-{$nextMonth['month']}", # $year . $month >= date('Ym') ? false : Router::urlFor('activitiesByMonth', array('year' => $nextMonth['year'], 'month' => $nextMonth['month'])), 
					'title'	=> date('F Y', $nextMonth['ts'])
				)
			);

			# Now get all the activities for this month
			$activities = Activities::get('pub_date', 'ASC', 0, 100000, "DATE_FORMAT(pub_date, '%Y') = '$year' AND DATE_FORMAT(pub_date, '%m') = '$month'");

			# And build the week/day array
			$blanks			= date('w', $firstDayOfMonth);
			$daysInMonth	= date('t', $firstDayOfMonth);
			$weeksInMonth	= ceil(($daysInMonth + $blanks) / 7);
			$actualDays		= 0;
			$todayDayNum	= date('d');

			for ($week = 0; $week < $weeksInMonth; $week++) {
				for ($day = 0; $day < 7; $day++) {
					if (($week == 0 and ($day < $blanks)) or ($actualDays >= $daysInMonth)) {
						self::$tplVars['weeks'][$week]['days'][$day]['blank'] = true;
					}
					else {
						$actualDays	   += 1;
						$numActivities	= 0;
						$dayNum			= $actualDays > 9 ? $actualDays : "0$actualDays";

						if ($activities) {
							foreach ($activities as $activity) {							

								if ($dayNum == $activity['day']) {
									$numActivities++;
								}
							}
						}

						self::$tplVars['weeks'][$week]['days'][$day] = array(
							'num_activities'	=> $numActivities, 
							'num'				=> $actualDays,	
							'url'				=> Router::urlForModule('Activities') . "&date=$year-$month-$dayNum", 
							'today'				=> $dayNum == $todayDayNum ? true : false
						);
					}
				}
			}
		}
	}
?>