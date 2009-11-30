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



class custom_text
	{
	function custom_text() 
		{
		$this->lang=get_showtime('lang');
		$this->global_custom_text=array();
		$this->property_uids_custom_text=array();
		$query="SELECT constant,customtext FROM #__jomres_custom_text WHERE property_uid = 0 AND language = '".$this->lang."'";
		$customTextList=doSelectSql($query);
		if (count($customTextList))
			{
			$customTextArray=array();
			foreach ($customTextList as $text)
				{
				$theConstant=str_replace("sc<x>ript","script",$text->constant);
				$this->global_custom_text[$theConstant]=stripslashes($text->customtext);
				}
			}
		}

	function get_custom_text_for_property($property_uid)
		{
		if ($property_uid > 0)
			$this->property_uid = $property_uid;
		$current_custom_text=$this->global_custom_text;
		$query="SELECT constant,customtext FROM #__jomres_custom_text WHERE property_uid = '".(int)$property_uid."' AND language = '".$this->lang."'";
		
		$customTextList=doSelectSql($query);
		if (count($customTextList))
			{
			$customTextArray=array();
			foreach ($customTextList as $text)
				{
				$theConstant=str_replace("sc<x>ript","script",$text->constant);
				$current_custom_text[$theConstant]=stripslashes($text->customtext);
				//echo $theConstant." - ".$text->customtext."<br>
				//";
				}
			//echo $property_uid." ".$current_custom_text."  CUSTOM<br>";
			$this->property_uids_custom_text[(int)$property_uid]= $current_custom_text;
			}
		return $current_custom_text;
		}

	function get_custom_text()
		{
		$property_specific_text = array();
		if (isset($this->property_uids_custom_text[(int)$this->property_uid]))
			$property_specific_text = $this->property_uids_custom_text[(int)$this->property_uid];
		$result = array_merge ($this->global_custom_text,$property_specific_text);
		return $result;
		}
	}

?>