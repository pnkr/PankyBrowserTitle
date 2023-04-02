<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.pankybrowsertitle
 *
 * @copyright   (C) 2023 Panagiotis Kiriakopoulos. <https://github.com/pnkr>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemPankybrowsertitle extends Joomla\CMS\Plugin\CMSPlugin
{

	/**
	 * Load the language file on instantiation
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;	

   public function onAfterDispatch()
   {
      if (!Joomla\CMS\Factory::getApplication()->isClient('site')) return;

      $document = Joomla\CMS\Factory::getApplication()->getDocument();

      if (!($document instanceof Joomla\CMS\Document\HtmlDocument))
      {
         return;
      }

      	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		// Load inline asset
	  	$script = "
		  window.onload = function() {

			var pageTitle = document.title;
			var attentionMessage = '" . $this->params->get('browsermessage') . "';
			var blinkEvent = null;
			var currentDomain = window.location.hostname;
		  
			document.addEventListener('visibilitychange', function(e) {
			  var isPageActive = !document.hidden;
		  
			  if (!isPageActive) {
				blink();
			  } else {
				document.title = pageTitle;
				clearInterval(blinkEvent);
			  }
			});
		  
			function blink() {
			  blinkEvent = setInterval(function() {
				if (document.title === attentionMessage) {
				  document.title = pageTitle;
				} else {
				  document.title = attentionMessage;
				}
			  }, 100);
			}
		  
			window.addEventListener('blur', function() {
			  // Save the current domain name when the user switches to a new tab
			  currentDomain = window.location.hostname;
			});
		  
			window.addEventListener('focus', function() {
			  // Check if the user switched to a new site when the tab regained focus
			  if (window.location.hostname !== currentDomain) {
				document.title = attentionMessage;
				blink();
			  } else {
				document.title = pageTitle;
				clearInterval(blinkEvent);
			  }
			});
		  };
		  
		";

		$wa->addInlineScript($script, ['name' => 'pankybrowsertitle.asset']);
   }
}