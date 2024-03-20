<?php
/**
 * @package Unlimited Elements
 * @author unlimited-elements.com
 * @copyright (C) 2021 Unlimited Elements, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorAPIIntegrations{
	
	const HOOK_GET_TYPES = "ue_api_get_types";
	const HOOK_GET_DATA = "ue_api_get_data";
	
	
	/**
	 * return if the api integration enabled
	 */
	public static function isEnabled(){
		
		$isEnabled = GlobalsUC::$enableAPIIntegration;
		
		return($isEnabled);
	}
	
	
	/**
	 * init the api integration, started from the external plugin
	 * on plugins_loaded hook
	 */
	public function init(){
		
		GlobalsUC::$enableAPIIntegration = true;
		
	}
	
}