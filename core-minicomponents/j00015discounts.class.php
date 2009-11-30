<?php
/**
 * Core file
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 4 
* @package Jomres
* @copyright	2005-2009 Vince Wooll
* Jomres is currently available for use in all personal or commercial projects under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly. 
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################

/**
#
 * xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
 #
* @package Jomres
#
 */
class j00015discounts
	{
	/**
	#
	 * xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	#
	 */
	function j00015discounts($componentArgs)
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return 
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=true; return;
			}
		$mrConfig=getPropertySpecificSettings();
		$property_uid=$componentArgs['property_uid'];
		if ($mrConfig['singleRoomProperty'] == 1)  // Using last minute calculations
			{
			$this->returnValue=array();
			$query="SELECT akey,value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='lastminuteactive' AND `value`='1' LIMIT 1";
			$lastminSettings = doSelectSql($query);
			if (count($lastminSettings)>0)
				{
				$query="SELECT value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='lastminutethreshold' LIMIT 1";
				$lastminutethreshold = doSelectSql($query,1);
				$query="SELECT value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='lastminutediscount' LIMIT 1";
				$lastminutediscount = doSelectSql($query,1);
				
				$todaysDate=date("Y/m/d");
				$date_elements	 = explode("/",$todaysDate);
				$unixTodaysDate= mktime(0,0,0,$date_elements[1],$date_elements[2]+$lastminutethreshold,$date_elements[0]);
				$latestDate=JSCalmakeInputDates(date("Y/m/d",$unixTodaysDate));
				
				$text	=	jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE',_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE,false,true);
				$text	.=	$lastminutediscount;
				$text	.=	jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_MID',_JOMCOMP_LASTMINUTE_PROPERTYLIST_MID,false,true);
				$text	.=	$latestDate;
				$text	.=	jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST',_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST,false,true);

				$this->returnValue=array('LASTMINUTE'=>$text,'LASTMINUTECLASS'=>'jomres_message');
				}
			}
		else // Using wiseprice calculations
			{
			$this->returnValue=array();
			$query="SELECT akey,value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='wisepriceactive' AND `value`='1' LIMIT 1";
			$wisepriceSettings = doSelectSql($query);
			if (count($wisepriceSettings)>0)
				{
				$query="SELECT value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='wisepricethreshold' LIMIT 1";
				$wisepricethreshold = doSelectSql($query,1);
				$query="SELECT value FROM #__jomres_settings WHERE property_uid = '".(int)$property_uid."' AND `akey`='wiseprice75discount' LIMIT 1";
				$wisepricediscount = doSelectSql($query,1);
				
				$todaysDate=date("Y/m/d");
				$date_elements	 = explode("/",$todaysDate);
				$unixTodaysDate= mktime(0,0,0,$date_elements[1],$date_elements[2]+$wisepricethreshold,$date_elements[0]);
				$latestDate=JSCalmakeInputDates(date("Y/m/d",$unixTodaysDate));

				$text	=	jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE',_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE,false,true);
				$text	.=	(float)$wisepricediscount.jr_gettext('_JOMCOMP_LASTMINUTE_ORMORE',_JOMCOMP_LASTMINUTE_ORMORE,false,true);
				$text	.=	$latestDate;
				$text	.=	jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST',_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST,false,true);

				$this->returnValue=array('LASTMINUTE'=>$text,'LASTMINUTECLASS'=>'jomres_message');
				}
			}
		}

	function touch_template_language()
		{
		$output=array();
		
		$output[]		=jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE',_JOMCOMP_LASTMINUTE_PROPERTYLIST_PRE);
		$output[]		=jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_MID',_JOMCOMP_LASTMINUTE_PROPERTYLIST_MID);
		$output[]		=jr_gettext('_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST',_JOMCOMP_LASTMINUTE_PROPERTYLIST_POST);
		$output[]		=jr_gettext('_JOMCOMP_LASTMINUTE_ORMORE',_JOMCOMP_LASTMINUTE_ORMORE);

		foreach ($output as $o)
			{
			echo $o;
			echo "<br/>";
			}
		}
	/**
	#
	 * Must be included in every mini-component
	#
	 * Returns any settings the the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	#
	 */
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return $this->returnValue;
		}

	}
?>