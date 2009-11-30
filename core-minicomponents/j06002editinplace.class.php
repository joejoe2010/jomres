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


class j06002editinplace 
	{
	function j06002editinplace()
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
		$thisJRUser=jomres_getSingleton('jr_user');
		if (!$thisJRUser->userIsManager)
			return;
		$siteConfig = jomres_getSingleton('jomres_config_site_singleton');
		$jrConfig=$siteConfig->get();
		$property_uid=(int)getDefaultProperty();
		if ($jrConfig['allowHTMLeditor'] == "1")
			{
			$customText = jomresGetParam( $_POST, 'newtext', "" , _MOS_ALLOWHTML );
			$theConstant = jomresGetParam( $_POST, 'theConstant', '' );
			}
		else
			{
			$customText = jomresGetParam( $_POST, 'newtext', '','string' );
			$theConstant = jomresGetParam( $_POST, 'theConstant', '' );
			}
		$result=updateCustomText($theConstant,$customText,$property_uid);
		if ($result)
			echo $customText;
		else
			echo "Something burped";
		}


	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}
	}
?>