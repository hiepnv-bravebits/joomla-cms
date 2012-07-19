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
 * class for dropdown menu
 *
 * @package     Joomla.Platform
 * @subpackage  HTML 
 */
abstract class JHtmlDropdown
{
	protected static $loaded = array();
	
	protected static $dropDownList = null;
	protected static $javascript = null;
	
	/**
	 * Method to inject needed script 
	 *	
	 * @return  void
	 * 
	 */
	
	public static function init()
	{
		// Only load once
		if (isset(self::$loaded[__METHOD__]))
		{
			return;
		}				
		
		JFactory::getDocument()->addScriptDeclaration(
			"
			(function($){
				$(document).ready(function (){
					$('.has-context')
					.mouseenter(function (){					
						$('.btn-group',$(this)).show();
					})
					.mouseleave(function (){				
						$('.btn-group',$(this)).hide();
						$('.btn-group',$(this)).removeClass('open');
					});
					
					contextAction =function (cbId, task) {
						$('input[name=\"cid[]\"]').removeAttr('checked');	
						$('#' + cbId).attr('checked','checked');					
						Joomla.submitbutton(task);
					}	
				});
			})(jQuery);
			"
		);		
		// Set static array
		self::$loaded[__METHOD__] = true;
		return;
	}
	
	/**
	 * Method to start a new dropdown menu 
	 *
	 * 	
	 * @return  void
	 * 
	 */
	public static function start()
	{				
		// Only start once
		if (isset(self::$loaded[__METHOD__]) && self::$loaded[__METHOD__] == true)
		{			
			return;
		}
		
		$dropDownList	= self::$dropDownList;
		$dropDownList 	= '<div class="btn-group" style="margin-left:6px;display:none">
		 						<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-mini">
		 							<span class="caret"></span>
		 						</a>
		 					<ul class="dropdown-menu">';
		self::$dropDownList	= $dropDownList;
		self::$loaded[__METHOD__] = true;
		return;
		 
	}
	
	/**
	 * Method to render current dropdown menu 
	 *	
	 * @return  void
	 * 
	 */
	public static function render()
	{
		$dropDownList	= self::$dropDownList;
		$dropDownList 	.= '</ul></div>';
		self::$dropDownList	= null;
		self::$loaded['JHtmlDropdown::start'] = false;
		
		return $dropDownList;
		 
	}
	
	/**
	 * Append Edit item to current dropdown menu
	 *
	 * @param	string	$task			Task name (.ex: articles.edit)
	 * @param	int		$id 			Id of the record
	 * @param	string	$customLink 	The custom link if dont use default Joomla action format	 
	 */
	
	public static function edit($id, $prefix = '', $customLink = '')
	{	
		self::start();	
		$link = '';
		if (!$customLink) {
			$option = JRequest::getVar('option');
			$link 	= 'index.php?option=' . $option;		
		}else{
			$link	= $customLink;
		}		
		$link .= '&task=' . $prefix . 'edit&id=' . $id;
		$link = JRoute::_($link);
		
		self::addCustomItem(JText::_('JACTION_EDIT'), $link);
		return;		 
	}
	
	/**
	 * Append Publish item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix			The task prefix
	 */
	public static function publish($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'publish';
		self::addCustomItem(JText::_('JTOOLBAR_PUBLISH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Unpublish item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function unpublish($checkboxId, $prefix = '')
	{	
		$task = $prefix . 'unpublish';	
		self::addCustomItem(JText::_('JTOOLBAR_UNPUBLISH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Featured item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function featured($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'featured';
		self::addCustomItem(JText::_('JFEATURED'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Unfeatured item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function unfeatured($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'unfeatured';
		self::addCustomItem(JText::_('JUNFEATURED'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Archive item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function archive($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'archive';
		self::addCustomItem(JText::_('JTOOLBAR_ARCHIVE'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Unarchive item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function unarchive($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'unpublish';
		self::addCustomItem(JText::_('JTOOLBAR_UNARCHIVE'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Trash item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 			The task prefix
	 */
	public static function trash($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'trash';
		self::addCustomItem(JText::_('JTOOLBAR_TRASH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Untrash item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 		The task prefix
	 */
	public static function untrash($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'publish';
		self::addCustomItem(JText::_('JTOOLBAR_UNTRASH'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Append Checkin item to current dropdown menu
	 *
	 * @param	string	$checkboxId		Id of corresponding checkbox of the record
	 * @param	int		$prefix 		The task prefix
	 */
	public static function checkin($checkboxId, $prefix = '')
	{		
		$task = $prefix . 'checkin';
		self::addCustomItem(JText::_('JTOOLBAR_CHECKIN'), 'javascript:void(0)', 'onclick="contextAction(\'' . $checkboxId . '\', \'' . $task . '\')"' );
		return;		 
	}
	
	/**
	 * Writes a divider between dropdown items
	 *	
	 */
	public static function divider()
	{		
		self::$dropDownList .= '<li class="divider"></li>';
		return;		 
	}
	
	/**
	 * Append Custom item to current dropdown menu
	 *
	 * @param	string	$label				The label of item
	 * @param	string	$link 				The link of item
	 * @param	string	$linkAttributes 	Custom link attributes
	 * @param	string	$className 			Class name of item
	 * @param	bool	$ajaxLoad 			True if using ajax load when item clicked
	 * @param	string	$jsCallBackFunc 	Javascript function name, called when ajax load successfully
	 */
	public static function addCustomItem ($label, $link = 'javascript:void(0)', $linkAttributes = '', $className = '', $ajaxLoad = false, $jsCallBackFunc = null)
	{	
		self::start();
		$href = '';
		
		if ($ajaxLoad) {
			$href = ' href = "javascript:void(0)" onclick="loadAjax(\'' . $link . '\', \''.$jsCallBackFunc.'\')"';
		}else{
			$href = ' href = "' . $link . '" ';
		}
		
		
		$dropDownList	= self::$dropDownList;
		$dropDownList	.= '<li class="' . $className . '"><a ' . $linkAttributes . $href . ' >';
		$dropDownList	.= $label;
		$dropDownList	.= '</a></li>';
		self::$dropDownList	= $dropDownList;
		return;
	}
	
}