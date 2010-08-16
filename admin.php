<?php
/**
 * Core file
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 4 
* @package Jomres
* @copyright	2005-2010 Vince Wooll
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly, however all images, css and javascript which are copyright Vince Wooll are not GPL licensed and are not freely distributable. 
**/


defined( '_JOMRES_INITCHECK' ) or die( '' );
defined( '_JOMRES_INITCHECK_ADMIN' ) or die( 'Admin Access to this file is not allowed.' );

ob_start();
@ini_set("memory_limit","64M");
@ini_set("max_execution_time","480");
@ini_set("display_errors",1);
//error_reporting(E_ALL);
@ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);

require_once(dirname(__FILE__).'/integration.php');
$MiniComponents =jomres_getSingleton('mcHandler');
$siteConfig = jomres_getSingleton('jomres_config_site_singleton');
$jrConfig=$siteConfig->get();

$tmpBookingHandler =jomres_getSingleton('jomres_temp_booking_handler');
$tmpBookingHandler->initBookingSession(get_showtime('jomressession'));
$jomressession  = $tmpBookingHandler->getJomressession();

$showSearchOptions=true;
$jomreslang =jomres_getSingleton('jomres_language');
$jomreslang->get_language('xx');
$customTextObj =jomres_getSingleton('custom_text');

if (!defined('JOMRES_IMAGELOCATION_ABSPATH'))
	{
	define('JOMRES_IMAGELOCATION_ABSPATH',JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'uploadedimages'.JRDS);
	define('JOMRES_IMAGELOCATION_RELPATH',get_showtime('live_site').'/jomres/uploadedimages/');
	}
$task = jomresGetParam( $_REQUEST, 'task', "" );
set_showtime('task',$task);

require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'jomresxml.functions.php');
require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'siteconfig.functions.php');
require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'propertyfeatures.functions.php');
require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'roomtypes.functions.php');
require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'propertytypes.functions.php');
require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'functions'.JRDS.'profiles.functions.php');

require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'admin'.JRDS.'admin.jomres.html.php');

$nohtml	= jomresGetParam( $_REQUEST, 'no_html',0 );

jr_import('jomres_obsolete_file_handling');
$obsolete_files = new jomres_obsolete_file_handling();
$obsolete_files->set_default_obs_files_array();
$obsolete_files->add_obs_file(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'components'.JRDS.'com_jomres'.JRDS.'jomres_webinstall.php');
if (jomresGetDomain() != "localhost")
	$obsolete_files->add_obs_file(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'install_jomres.php');

if ($obsolete_files->ready_to_go() )
	{
	$obsolete_files->remove_obs_files();
	$obsolete_files->output_file_deletion_warning();
	}

if (is_dir(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'plugins') && $nohtml == 0)
	{
	emptyDir(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'plugins');
	rmdir(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'plugins');
	if (is_dir(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'plugins') )
		echo '<font color="red" face="arial" size="1">Warning: directory '.JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'plugins still exists. Please delete it.</font><br/>';
	emptyDir(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'cache'.JRDS);
	}
	
if ($jrConfig['useSubscriptions']=="1" && $nohtml == "0")
	{
	$packages=subscriptions_packages_getallpackages();
	if (count($packages)==0)
		{
		echo '<font color="red" face="arial" size="1">Warning: You have enabled subscription handling, but not yet created any subscription packages therefore only Super Property Managers will be able to create propertys on your server.</font><br/>';
		}
	}
	
if (strstr($_SERVER['SCRIPT_NAME'],'index3.php') || $nohtml == "1")
	define('JRPORTAL_AJAXCALL',true);
else
	define('JRPORTAL_AJAXCALL',false);

$jomreslang =jomres_getSingleton('jomres_language');
$jomreslang->get_language($propertytype);
$customTextObj =jomres_getSingleton('custom_text');


if (!JRPORTAL_AJAXCALL)
	{
	echo $jomreslang->get_languageselection_dropdown()."<br/>";
	/*
	?>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jquery-1.3.2.min.js"></script>
	<script language="javascript" type="text/javascript">jQuery.noConflict();</script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jquery.cookee.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jomres.js"></script>
	<script language="javascript"type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/tablesort.js"></script>
	<script  language="javascript"type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/tablepaginator.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/graphs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jrportal.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jquery.jeditable.pack.js"></script>

	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jquery.bt.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/excanvas-compressed.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo get_showtime('live_site'); ?>/jomres/css/jomrescss.css" title="" />
	<div id='jomresmenu_hint' style=color:red; >&nbsp;</div>
	<?php
	*/
	
	init_javascript();
	// And a couple that are only used in the admin area
	?>
	<script language="javascript"type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/tablesort.js"></script>
	<script  language="javascript"type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/tablepaginator.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/graphs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_showtime('live_site'); ?>/jomres/javascript/jrportal.js"></script>
	<div id='jomresmenu_hint' style=color:red; >&nbsp;</div>
	<?php
	if (strlen(get_showtime('task'))>1)
		{
		jr_import('cpanel');
		$cpanel=new cpanel();
		}
	}
if (isset($_REQUEST['statoption']))
	{
	$statoption 	= jomresGetParam( $_REQUEST, 'statoption', '');
	SetCookie("statoption", $statoption, time()+60*60);	
	}
	
if (isset($_REQUEST['periodoption']))
	{
	$periodoption 	= jomresGetParam( $_REQUEST, 'periodoption', '');
	SetCookie("periodoption", $periodoption, time()+60*60);
	}

if (!JRPORTAL_AJAXCALL)
	{
	if (defined('_JOMRES_NEWJOOMLA'))
		$indexphp="index.php";
	else
		{
		echo '<script language="JavaScript" src="'.get_showtime('live_site').'/includes/js/joomla.javascript.js" type="text/javascript"></script>';
		$indexphp="index2.php";	
		}
	}
else
	$indexphp="index3.php";
	

switch (get_showtime('task')) {
	case "convertCustomTextAll":
		convertCustomTextAll();
		break;
	case "publishPropertyType":
		publishPropertyType();
		break;
	case "deletePropertyType":
		deletePropertyType();
		break;
	case "savePropertyType":
		savePropertyType();
		break;
	case "editPropertyType":
		editPropertyType();
		break;
	case "changeUserHotel":
		changeUserHotel($option);
		break;
	case "changeUserAccessLevel":
		changeUserAccessLevel();
		break;
	case "listMosUsers":
		listMosUsers($option);
		break;
	case "editProfile":
		editProfile();
		break;
	case "saveProfile":
		saveProfile();
		break;
	case "grantMosUser":
		grantMosUser($option);
		break;
	case "showSiteConfig":
		showSiteConfig(  );
		break;
	case "saveSiteConfig":
		saveSiteConfig(  );
		break;
	case "publishPfeature":
		publishPfeature();
		break;
	case 'cpanel':
		default:
		if ($MiniComponents->eventSpecificlyExistsCheck('16000',get_showtime('task')) )
			{
			$MiniComponents->specificEvent('16000',get_showtime('task')); // Custom task
			}
		else
			{
			$version=$mrConfig['version'];
			HTML_jomres::controlPanel($version);
			}
		break;
	}
if (defined("JOMRES_RETURNDATA") )
	{
	define("JOMRES_RETURNDATA_CONTENT", ob_get_contents() ) ;
	ob_end_clean();
	}
else
	ob_end_flush();
?>