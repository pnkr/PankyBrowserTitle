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

	  $document->setMetaData('browserTabMsg', $this->params->get('browsermessage'));


      if (!($document instanceof Joomla\CMS\Document\HtmlDocument))
      {
         return;
      }

      	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->getRegistry()->addExtensionRegistryFile('plg_system_pankybrowsertitle');

		$wa->useScript('pankybrowsertitle','script');

   }
}