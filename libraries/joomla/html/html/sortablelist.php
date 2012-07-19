<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * class for sortable table list
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class JHtmlSortablelist
{
	protected static $loaded = array();
	
	/**
	 * Method to load the Sortable script and make table sortable 
	 *	 
	 *
	 * @param   string  $tableId  DOM id of the table
	 * @param   string  $formId   DOM id of the form
	 * @param   string  $saveOrderingUrl save ordering url, ajax-load after an item dropped 
	 * @return  void
	 * 
	 */
	public static function sortable ($tableId, $formId, $saveOrderingUrl, $sortDir = 'asc' , $_proceedSaveOrderButton = true)
	{	
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}

		JHtml::_('script', 'system/sortablelist.js', false, true);		
		JHtml::_('stylesheet', 'sortablelist.css', array(), true);
		// Attach sortable to document
		JFactory::getDocument()->addScriptDeclaration(
			"
			(function ($){
				$(document).ready(function (){
					var sortableList = new $.JSortableList('#" . $tableId . " tbody','" . $formId . "','" . $sortDir . "' , '" . $saveOrderingUrl . "');
				});
			})(jQuery);
			"
		);
		if($_proceedSaveOrderButton) {
			self::_proceedSaveOrderButton();
		}	
		// Set static array
		self::$loaded[__METHOD__] = true;
		return;
	}
	
	/**
	 * Method to inject script for enabled and disable Save order button 
	 * when changing value of ordering input boxes 
	 *
	 * @return  void
	 * 
	 */
	public static function _proceedSaveOrderButton()
	{
		JFactory::getDocument()->addScriptDeclaration(
			"(function ($){
				$(document).ready(function (){
					var saveOrderButton = $('.saveorder');
					saveOrderButton.css({'opacity':'0.2', 'cursor':'default'}).attr('onclick','return false;');			
					var oldOrderingValue = '';	
					$('.text-area-order').focus(function () {
						oldOrderingValue = $(this).attr('value');
					})
					.keyup(function (){
						var newOrderingValue = $(this).attr('value');			
						if(oldOrderingValue != newOrderingValue) {
							saveOrderButton.css({'opacity':'1', 'cursor':'pointer'}).removeAttr('onclick')
						}
					});
				});	
			})(jQuery);"
		);
		return;
	}
	
}