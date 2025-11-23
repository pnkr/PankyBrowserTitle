<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.pankybrowsertitle
 *
 * @copyright   (C) 2023 Panagiotis Kiriakopoulos. <https://github.com/pnkr>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Pnkr\Plugin\System\Pankybrowsertitle\Extension;

use Joomla\CMS\Factory;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Browser Title Plugin
 *
 * @since  5.0.0
 */
final class Pankybrowsertitle extends CMSPlugin implements SubscriberInterface
{
    /**
     * Load the language file on instantiation
     *
     * @var    boolean
     * @since  5.0.0
     */
    protected $autoloadLanguage = true;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterDispatch' => 'onAfterDispatch',
        ];
    }

    /**
     * After dispatch event handler
     *
     * @return  void
     *
     * @since   5.0.0
     */
    public function onAfterDispatch(): void
    {
        // Only run on frontend
        if (!$this->getApplication()->isClient('site')) {
            return;
        }

        $document = $this->getApplication()->getDocument();

        // Only proceed if this is an HTML document
        if (!($document instanceof HtmlDocument)) {
            return;
        }

        // Get message mode and prepare messages
        $messageMode = $this->params->get('message_mode', 'single');
        $messages = [];

        if ($messageMode === 'multiple') {
            // Get messages from subform
            $messagesData = $this->params->get('messages');
            
            // Handle different data formats from Joomla subform
            if (is_string($messagesData)) {
                // Decode JSON string
                $messagesData = json_decode($messagesData, true);
            }

            if (is_object($messagesData)) {
                // Convert object to array
                $messagesData = (array) $messagesData;
            }

            if (is_array($messagesData) && !empty($messagesData)) {
                foreach ($messagesData as $item) {
                    // Handle both array and object items
                    if (is_object($item)) {
                        $item = (array) $item;
                    }
                    
                    if (is_array($item) && isset($item['message']) && !empty($item['message'])) {
                        $messages[] = trim($item['message']);
                    } elseif (is_string($item) && !empty($item)) {
                        // Fallback: treat the item itself as a message
                        $messages[] = trim($item);
                    }
                }
            }
        }

        // Fallback to single message mode if no multiple messages configured
        if (empty($messages)) {
            $singleMessage = $this->params->get('browsermessage', '');
            if (!empty($singleMessage)) {
                $messages[] = $singleMessage;
            }
        }

        // Set messages as JSON in meta tag
        if (!empty($messages)) {
            $document->setMetaData('browserTabMsg', json_encode($messages));
        }

        // Set return notification settings
        $enableReturn = (bool) $this->params->get('enable_return_message', false);
        if ($enableReturn) {
            $returnMessage = $this->params->get('return_message', 'Welcome back! 🎉');
            $returnDuration = (int) $this->params->get('return_duration', 2000);
            
            $document->setMetaData('browserTabReturnMsg', $returnMessage);
            $document->setMetaData('browserTabReturnDuration', (string) $returnDuration);
        }

        // Register and use the WebAsset
        /** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
        $wa = $document->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('plg_system_pankybrowsertitle');
        $wa->useScript('pankybrowsertitle');
    }
}
