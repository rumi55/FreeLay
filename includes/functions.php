<?php

			#function to generate random numbers
			function rand_num() {
				
				$r  = rand(1000000, 9999999);
				return $r;
			
			}

			#function to generate random milestone numbers
			function random_numbers($ms) {
				
				$n = rand(0, $ms);
				
				return $n;
				
			}
			
			function bad_milestone($ms) {
				
				$min = $ms+1;
				$max = $ms*4;
				 
				 $n = rand($min, $max);
				 
				 return $n;
				 
			}
			
			#function to get the future date
			function get_date($day, $year)    {
				
				$number_date =  date('D d F, Y', mktime(0, 0, 0, 0+1, $day, $year));
				
				return $number_date;
			
			}
			
			#function to return the total days to the milestone specified
			function get_total_days($the_time, $the_target) {
			
					switch($the_time) {
						
						case 'days':
						
						$days = $the_target;
						break;
						
						case 'weeks':
						
						$days = $the_target * 7;
						break;
						
						case 'months':
						
						$days = $the_target * 30;
						break;
						
						case 'years':
						
						$days = $the_target * 365;
						break;
						
					}
					
					return $days;
			
			}
						
						