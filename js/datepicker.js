
	var datePickerDivID = "datepicker";

	var iFrameDivID = "datepickeriframe";

	

	var dayArray = new Array('Sa','Su', 'Mo', 'Tu', 'We', 'Th', 'Fr');

	var monthArray = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

	var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

	 

	var defaultDateSeparator = "/";        // common values would be "/" or "."

	var defaultDateFormat = "dmy"    // valid values are "mdy", "dmy", and "ymd"

	

	var dateSeparator = defaultDateSeparator;

	var dateFormat = defaultDateFormat;

	

	function CopyText(SourceFeild, DestinationFeild) {	

		sdate = document.getElementsByName (SourceFeild).item(0).value;

		document.getElementsByName (DestinationFeild).item(0).value = sdate;		

	}

	

	function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)

	{

	  var targetDateField = document.getElementsByName (dateFieldName).item(0);

	 

	  if (!displayBelowThisObject)

		displayBelowThisObject = targetDateField;



	  if (dtSep)

		dateSeparator = dtSep;

	  else

		dateSeparator = defaultDateSeparator;



	  if (dtFormat)

		dateFormat = dtFormat;

	  else

		dateFormat = defaultDateFormat;

	 

	  var x = displayBelowThisObject.offsetLeft;

	  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;



	  var parent = displayBelowThisObject;

	  while (parent.offsetParent) {

		parent = parent.offsetParent;

		x += parent.offsetLeft;

		y += parent.offsetTop;

	  }

	 

	  drawDatePicker(targetDateField, x, y);

	}

	

	

	function drawDatePicker(targetDateField, x, y)

	{

	  var dt = getFieldDate(targetDateField.value);

	  

	  if (!document.getElementById(datePickerDivID)) {

		var newNode = document.createElement("div");

		newNode.setAttribute("id", datePickerDivID);

		newNode.setAttribute("class", "dpDiv");

		newNode.setAttribute("style", "visibility: hidden;");
		newNode.setAttribute("style", "background:#F88;");

		document.body.appendChild(newNode);

	  }



	  var pickerDiv = document.getElementById(datePickerDivID);

	  pickerDiv.style.position = "absolute";

	  pickerDiv.style.left = x + "px";

	  pickerDiv.style.top = y + "px";

	  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");

	  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");

	  pickerDiv.style.zIndex = 10000;



	  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());

	}

	

	function refreshDatePicker(dateFieldName, year, month, day)

	{

	  // if no arguments are passed, use todays date; otherwise, month and year

	  // are required (if a day is passed, it will be highlighted later)

	  var thisDay = new Date();

	 

	  if ((month >= 0) && (year > 0)) {

		thisDay = new Date(year, month, 1);

	  } else {

		day = thisDay.getDate();

		thisDay.setDate(1);

	  }

	 

	  // the calendar will be drawn as a table

	  // you can customize the table elements with the file resman_style.css,

	  var crlf = "\r\n";

	  var TABLE = "<table cols=7 class='dpTable'>" + crlf;

	  var xTABLE = "</table>" + crlf;

	  var TR = "<tr class='dpTR'>";

	  var TR_title = "<tr class='dpTitleTR'>";

	  var TR_days = "<tr class='dpDayTR'>";

	  var TR_todaybutton = "<tr class='dpTodayButtonTR'>";

	  var xTR = "</tr>" + crlf;

	  var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because well be adding an onClick event

	  var TD_title = "<td colspan=5 class='dpTitleTD'>";

	  var TD_buttons = "<td class='dpButtonTD'>";

	  var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";

	  var TD_days = "<td class='dpDayTD'>";

	  var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we will be adding an onClick event

	  var xTD = "</td>" + crlf;

	  var DIV_title = "<div class='dpTitleText'>";

	  var DIV_selected = "<div class='dpDayHighlight'>";

	  var xDIV = "</div>";

	  var html = TABLE;

	  html += TR_title;

	  html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;

	  html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;

	  html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;

	  html += xTR;

	  html += TR_days;

	  for(i = 0; i < dayArray.length; i++)

		html += TD_days + dayArray[i] + xTD;

	  html += xTR;

	  html += TR;

	  

	  var weekStartday = 6;	  

	  var freeDays = 0;

	  if (thisDay.getDay() < weekStartday){

	  	freeDays = 7 - (weekStartday - thisDay.getDay());

	  } else {

	  	freeDays = thisDay.getDay() - weekStartday; 

	  }

	  

	  for (i = 0; i < freeDays; i++){

		html += TD + "&nbsp;" + xTD;

	  }	

		

	  do {

		dayNum = thisDay.getDate();

		TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";

		

		if (dayNum == day)

		  html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;

		else

		  html += TD + TD_onclick + dayNum + xTD;

		

		// if this is a Saturday, start a new row

		// NEW: if this is a last day in datepicker		

		if ((thisDay.getDay() + 1) % 7 == weekStartday)

		  html += xTR + TR;

		

		// increment the day

		thisDay.setDate(thisDay.getDate() + 1);

	  } while (thisDay.getDate() > 1)

	 

	  // fill in any trailing blanks

	  if (thisDay.getDay() > 0) {

		for (i = 6; i > thisDay.getDay(); i--)

		  html += TD + "&nbsp;" + xTD;

	  }

	  html += xTR;

	  var today = new Date();

	  html += TR_todaybutton + TD_todaybutton;

	  html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>This Month</button> ";

	  html += xTD + xTR;

	  html += xTABLE;

	  document.getElementById(datePickerDivID).innerHTML = html;

	  adjustiFrame();

	}

	

	function getButtonCode(dateFieldName, dateVal, adjust, label)

	{

	  var newMonth = (dateVal.getMonth () + adjust) % 12;

	  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);

	  if (newMonth < 0) {

		newMonth += 12;

		newYear += -1;

	  }

	 

	  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";

	}



	function getDateString(dateVal)

	{

	  var dayString = "00" + dateVal.getDate();

	  var monthString = "00" + (dateVal.getMonth()+1);

	  dayString = dayString.substring(dayString.length - 2);

	  monthString = monthString.substring(monthString.length - 2);

	 

	  switch (dateFormat) {

		case "dmy" :

		  return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();

		case "ymd" :

		  return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;

		case "mdy" :

		default :

		  return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();

	  }

	}



	function getFieldDate(dateString)

	{

	  var dateVal;

	  var dArray;

	  var d, m, y;

	 

	  try {

		dArray = splitDateString(dateString);

		if (dArray) {

		  switch (dateFormat) {

			case "dmy" :

			  d = parseInt(dArray[0], 10);

			  m = parseInt(dArray[1], 10) - 1;

			  y = parseInt(dArray[2], 10);

			  break;

			case "ymd" :

			  d = parseInt(dArray[2], 10);

			  m = parseInt(dArray[1], 10) - 1;

			  y = parseInt(dArray[0], 10);

			  break;

			case "mdy" :

			default :

			  d = parseInt(dArray[1], 10);

			  m = parseInt(dArray[0], 10) - 1;

			  y = parseInt(dArray[2], 10);

			  break;

		  }

		  dateVal = new Date(y, m, d);

		} else if (dateString) {

		  dateVal = new Date(dateString);

		} else {

		  dateVal = new Date();

		}

	  } catch(e) {

		dateVal = new Date();

	  }

	 

	  return dateVal;

	}

	

	function splitDateString(dateString)

	{

	  var dArray;

	  if (dateString.indexOf("/") >= 0)

		dArray = dateString.split("/");

	  else if (dateString.indexOf(".") >= 0)

		dArray = dateString.split(".");

	  else if (dateString.indexOf("-") >= 0)

		dArray = dateString.split("-");

	  else

		dArray = false;

	 

	  return dArray;

	}

	

	

	function updateDateField(dateFieldName, dateString)

	{

	  var targetDateField = document.getElementsByName(dateFieldName).item(0);

	  if (dateString) {

		targetDateField.value = dateString;

	  }

	  

	  var pickerDiv = document.getElementById(datePickerDivID);

	  pickerDiv.style.visibility = "hidden";

	  pickerDiv.style.display = "none";

	  

	  adjustiFrame();	  

	  targetDateField.focus();	  

	 

	  if ((dateString) && (typeof(datePickerClosed) == "function")){	

	  	window.setTimeout('IEFix("'+dateFieldName+'")', 100);		

	  }

	}			

	

	function IEFix(dateFieldName){

		var targetDateField = document.getElementsByName(dateFieldName).item(0);

		datePickerClosed(targetDateField);

	}

	

	function adjustiFrame(pickerDiv, iFrameDiv)

	{

	  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);

	  if (is_opera)

		return;

	  try {

		if (!document.getElementById(iFrameDivID)) {

		  var newNode = document.createElement("iFrame");

		  newNode.setAttribute("id", iFrameDivID);

		  newNode.setAttribute("src", "javascript:false;");

		  newNode.setAttribute("scrolling", "no");

		  newNode.setAttribute ("frameborder", "0");

		  document.body.appendChild(newNode);

		}

		

		if (!pickerDiv)

		  pickerDiv = document.getElementById(datePickerDivID);

		if (!iFrameDiv)

		  iFrameDiv = document.getElementById(iFrameDivID);

		

		try {

		  iFrameDiv.style.position = "absolute";

		  iFrameDiv.style.width = pickerDiv.offsetWidth;

		  iFrameDiv.style.height = pickerDiv.offsetHeight ;

		  iFrameDiv.style.top = pickerDiv.style.top;

		  iFrameDiv.style.left = pickerDiv.style.left;

		  iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;

		  iFrameDiv.style.visibility = pickerDiv.style.visibility ;

		  iFrameDiv.style.display = pickerDiv.style.display;

		} catch(e) {

		}

	 

	  } catch (ee) {	  	

	  }	 

	}// JavaScript Document