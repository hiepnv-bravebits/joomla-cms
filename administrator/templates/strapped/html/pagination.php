<?php
/**
 * @version		$Id: pagination.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die;

/**
 * This is a file to add template specific chrome to pagination rendering.
 *
 * pagination_list_footer
 * 	Input variable $list is an array with offsets:
 * 		$list[limit]		: int
 * 		$list[limitstart]	: int
 * 		$list[total]		: int
 * 		$list[limitfield]	: string
 * 		$list[pagescounter]	: string
 * 		$list[pageslinks]	: string
 *
 * pagination_list_render
 * 	Input variable $list is an array with offsets:
 * 		$list[all]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[start]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[previous]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[next]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[end]
 * 			[data]		: string
 * 			[active]	: boolean
 * 		$list[pages]
 * 			[{PAGE}][data]		: string
 * 			[{PAGE}][active]	: boolean
 *
 * pagination_item_active
 * 	Input variable $item is an object with fields:
 * 		$item->base	: integer
 * 		$item->link	: string
 * 		$item->text	: string
 *
 * pagination_item_inactive
 * 	Input variable $item is an object with fields:
 * 		$item->base	: integer
 * 		$item->link	: string
 * 		$item->text	: string
 *
 * This gives template designers ultimate control over how pagination is rendered.
 *
 * NOTE: If you override pagination_item_active OR pagination_item_inactive you MUST override them both
 */

function pagination_list_footer($list)
{
	$html = "<div class=\"pagination pagination-toolbar\">\n";
	$html .= $list['pageslinks'];
	$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"".$list['limitstart']."\" />";
	$html .= "\n</div>";

	return $html;
}

function pagination_list_render($list)
{
	//calculate to display range of pages
	$currentPage = 1;
	$range = 1;
	$step  = 5;
	foreach ($list['pages'] as $k=>$page) {
		if (!$page['active']) {
			$currentPage = $k;
		} 		
	}
	if($currentPage >= $step ){
		if($currentPage % $step == 0){
			$range = ceil($currentPage/$step) + 1;
		}else{
			$range = ceil($currentPage/$step);
		}		
	}
	
	// Initialize variables
	$html = "<ul class=\"pagination-list\">";
			
	$list['start']['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1<span class="icon-fast-backward"></span>$2', $list['start']['data']);
	$list['previous']['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1<span class="icon-step-backward"></span>$2', $list['previous']['data']);
	
	$html .= $list['start']['data'];
	$html .= $list['previous']['data'];
	foreach( $list['pages'] as $k=>$page )
	{
		if($page['data']['active']) {
		}
		
		if (in_array($k, range($range * $step - ($step + 1), $range*$step))){
			if(($k % $step == 0 || $k == $range * $step - ($step + 1)) && $k != $currentPage && $k != $range * $step - $step ){
				$page['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $page['data']);
			}
			
			$html .= $page['data'];			
		}
				if($page['data']['active']) {
		}
	}

	$list['next']['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1<span class="icon-step-forward"></span>$2', $list['next']['data']);
	$list['end']['data'] = preg_replace('#(<a.*?>).*?(</a>)#', '$1<span class="icon-fast-forward"></span>$2', $list['end']['data']);
	
	
	$html .= $list['next']['data'];
	$html .= $list['end']['data'];	

	$html .= "</ul>";
	return $html;
	
}
function pagination_item_active(&$item)
{
	if ($item->base>0)
		return "<li><a href=\"#\" title=\"".$item->text."\"  onclick=\"document.adminForm." . $item->prefix . "limitstart.value=".$item->base."; Joomla.submitform();return false;\">".$item->text."</a></li>";
	else
		return "<li><a href=\"#\" title=\"".$item->text."\"  onclick=\"document.adminForm." . $item->prefix . "limitstart.value=0; Joomla.submitform();return false;\">".$item->text."</a></li>";
}

function pagination_item_inactive(&$item) {
	return "<li class=\"disabled\"><a>".$item->text."</a></li>";
}
?>