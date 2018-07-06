
<?php
/**
 * demo_idb.php is both a test page for your IDB shared mysqli connection, and a starting point for 
 * building DB applications using IDB connections
 *
 * @package nmCommon
 * @author technerdlove
 * @version 1.0 2016/05
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @see footer_inc.php 
 * @todo none
 */
# '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

spl_autoload_register('MyAutoLoader::NamespaceLoader'); //

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription; 

//END CONFIG AREA ---------------------------------------------------------- 

//BEGIN OPERATIONAL AREA ---------------------------------------------

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring //words in the id field get changed to 0.  Databases don't have any records that are identified by 0
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

get_header(); #defaults to header_inc.php
?>
<h3 align="center">Survey View</h3>
<?php

$mySurvey = new SurveySez\Survey($myID); //put the SuveyID into the parenthesis. The Id number gets passed in to the sql query string in the class. //added SurveySez namespace
//dumpDie($mySurvey);

if($mySurvey->isValid)
{//if the survey exists, show data
	//echo '<p>Survey Title:<b>' .  $mySurvey->Title . '</b></p>';
	$mySurvey->showQuestions();
	
}else{ //apologize
	echo '<div>There appears to be no such survey</div>';
}
#######RESPONSE TIME

# currently 'hard wired' to one response - will need to pass in #id of a Response on the qstring  
$myResponse = new SurveySez\Response(1);

if($myResponse->isValid)
{# check to see if we have a valid SurveyID
	echo "Survey Title: <b>" . $myResponse->Title . "</b><br />";  # show data on page
	echo "Date Taken: " . $myResponse->DateTaken . "<br />";
	echo "Survey Description: " . $myResponse->Description . "<br />";
	echo "Number of Questions: " .$myResponse->TotalQuestions . "<br /><br />";
	echo $myResponse->showChoices() . "<br />";	# showChoices method shows all questions, and selected answers (choices) only!
	unset($myResponse);  # destroy object & release resources
}else{
	echo "Sorry, no such response!";	
}

########RESULTS
$myResult = new SurveySez\Result(1);

if($myResult->isValid)
{# check to see if we have a valid SurveyID
	echo "Survey Title: <b>" . $myResult->Title . "</b><br />";  //show data on page
	echo "Survey Description: " . $myResult->Description . "<br />";
	$myResult->showGraph() . "<br />";	//showTallies method shows all questions, answers and tally totals!
	unset($myResult);  //destroy object & release resources
}else{
	echo "Sorry, no results!";	
}

########FOOTER
get_footer(); #defaults to footer_inc.php

//END OPERATIONAL AREA -------------------------------------------------
?>









