<?php

//generate a random unique name for the image
			$hash = rand(1,10000).date('dmy');	//10000 encription range(can neva be exhausted nor repeated) and DATE OF UPLOAD
			
			$fullprefix = SITENAME.$hash;	
			$uniquefile = $fullprefix.'.csv';
			
			//declare these variables for the multiple images uploaded
			$uploaddir = "../uploads/".$uniquefile;
			
			$path = "../uploads/".$uniquefile;

?>			