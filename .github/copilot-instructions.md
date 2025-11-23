# Copilot Instructions - PankyBrowserTitle Joomla Plugin

## Project Overview
This is a **Joomla 5/6 system plugin** that changes the browser tab title when visitors switch away from the site. The plugin injects JavaScript that blinks a custom message (or rotates through multiple messages) in the browser tab to re-engage visitors who navigate away.

**Core Flow:**
1. Service provider (`services/provider.php`) registers the plugin with dependency injection
2. Extension class (`src/Extension/Pankybrowsertitle.php`) implements event subscriber pattern
3. Custom message(s) are passed from plugin config → meta tag (as JSON array) → JavaScript
4. JavaScript (`js/functions.js`) monitors page visibility and animates the tab title

## Key Features

### Multiple Message Rotation (v2.0+)
- Admin can choose between **single message** or **multiple rotating messages**
- When multiple messages configured, JavaScript rotates through them every 3 seconds
- Messages blink at 100ms interval (toggle between original title and current message)
- Backward compatible: Falls back to single message mode if no multiple messages set

### Return Notification (v2.0+)
- Optional welcome-back message when user returns to the tab
- Configurable message text (supports emoji): "Welcome back! 🎉"
- Configurable display duration (500-10000ms, default 2000ms)
- After duration expires, original page title is restored
- Can be disabled to restore title immediately

## Architecture & Key Files

### Plugin Structure (Joomla 5/6 Modern Convention)
- `services/provider.php` - Service provider for dependency injection container
- `src/Extension/Pankybrowsertitle.php` - Main plugin class implementing `SubscriberInterface`
- `pankybrowsertitle.xml` - Plugin manifest with namespace declaration
- `joomla.asset.json` - WebAsset registry defining JavaScript dependencies and loading behavior
- `js/functions.js` - Client-side logic for tab title manipulation
- `language/{locale}/plg_system_pankybrowsertitle.{ini|sys.ini}` - Translation strings

**Naming Convention:** 
- Files use lowercase `pankybrowsertitle` (no spaces/underscores)
- Namespace: `Pnkr\Plugin\System\Pankybrowsertitle`
- Translation keys use uppercase `PLG_SYSTEM_PANKYBROWSERTITLE_*`

### Data Flow Pattern
```
Admin Config → Service Provider → Extension Class → Meta Tags (JSON + Settings) → JavaScript → DOM Title
```
- Service provider (`provider.php`) registers plugin with DI container
- Extension class retrieves message mode and processes messages:
  - **Single mode:** Gets `browsermessage` param
  - **Multiple mode:** Gets `messages` subform data, extracts message array
- Encodes as JSON and sets meta tag: `$document->setMetaData('browserTabMsg', json_encode($messages))`
- Sets return notification settings in separate meta tags: `browserTabReturnMsg` and `browserTabReturnDuration`
- JavaScript parses JSON array, rotates through messages, and shows return notification with configurable duration

## Joomla-Specific Conventions

### Modern Dependency Injection (Joomla 5/6)
- **Service Provider Pattern:** `services/provider.php` returns anonymous class implementing `ServiceProviderInterface`
- **Namespace Declaration:** Manifest includes `<namespace path="src">Pnkr\Plugin\System\Pankybrowsertitle</namespace>`
- **Event Subscriber:** Extension class implements `SubscriberInterface` with `getSubscribedEvents()` method
- **Files Declaration:** Manifest uses `<folder plugin="pankybrowsertitle">services</folder>` and `<folder>src</folder>`

Example service provider registration:
```php
$container->set(
    PluginInterface::class,
    function (Container $container) {
        $plugin = new Pankybrowsertitle(
            $container->get(DispatcherInterface::class),
            (array) PluginHelper::getPlugin('system', 'pankybrowsertitle')
        );
        $plugin->setApplication(Factory::getApplication());
        return $plugin;
    }
);
```

### Event Subscription Pattern
Instead of magic methods, explicitly declare event subscriptions:
```php
public static function getSubscribedEvents(): array
{
    return [
        'onAfterDispatch' => 'onAfterDispatch',
    ];
}
```

### WebAsset System (Modern Joomla 4.x+)
- Use `joomla.asset.json` to register scripts (NOT inline `<script>` tags)
- Load assets via WebAssetManager in plugin:
  ```php
  $wa = $document->getWebAssetManager();
  $wa->getRegistry()->addExtensionRegistryFile('plg_system_pankybrowsertitle');
  $wa->useScript('pankybrowsertitle');
  ```
- Asset URIs use convention: `plg_{group}_{pluginname}/{file}.js`

### Plugin Event Hooks
- `onAfterDispatch` - Used here to inject assets after routing but before rendering
- Check client context: `$this->getApplication()->isClient('site')` to run only on frontend
- Verify document type: `$document instanceof HtmlDocument` before DOM manipulation

### Language Files
- `.ini` - Frontend/admin strings for rendered UI
- `.sys.ini` - System strings (plugin name/description in Extension Manager)
- Keys must match manifest: `PLG_{GROUP}_{PLUGINNAME}_*`

## Development Workflows

### Testing Changes
1. **Install plugin:** Copy to Joomla's `plugins/system/pankybrowsertitle/` directory
2. **Enable:** Extensions → Plugins → Enable "System - Browser title notification"
3. **Configure:** Set custom message in plugin settings
4. **Test:** Visit any frontend page, switch tabs, observe title animation

### File Location in Joomla Installation
- Service provider: `plugins/system/pankybrowsertitle/services/provider.php`
- Extension class: `plugins/system/pankybrowsertitle/src/Extension/Pankybrowsertitle.php`
- JavaScript: `media/plg_system_pankybrowsertitle/js/functions.js` (auto-copied from `js/` during install)
- Asset manifest: `media/plg_system_pankybrowsertitle/joomla.asset.json`

### Making Code Changes
- **PHP changes:** Edit files in `src/` or `services/`, reinstall plugin or clear cache
- **JavaScript changes:** Edit `js/functions.js` (will be copied to `media/` folder on install)
- **Config changes:** Update `pankybrowsertitle.xml` `<config>` section
- **Translation changes:** Edit `.ini` files

## Critical Technical Details

### JavaScript Visibility Detection
- Uses `document.visibilitychange` event (not deprecated `blur`/`focus` alone)
- Blinking implemented with 100ms `setInterval` toggling between original and custom title
- Cleanup: `clearInterval(blinkEvent)` when page regains focus

### Security & Compatibility
- Always check `defined('_JEXEC') or die;` at top of PHP files (Joomla security requirement)
- Use `autoloadLanguage = true` property for automatic language file loading
- Defer JavaScript loading: `"attributes": {"defer": true}` in asset definition

### Version Compatibility
- Manifest declares `type="plugin"` without version attribute (Joomla 5/6+ format)
- Plugin targets Joomla 5.x/6.x (uses modern namespace-based DI and event subscription)
- For Joomla 4.x compatibility, can use hybrid approach with both `pankybrowsertitle.php` and namespace structure
- For Joomla 3.x compatibility, would need completely different structure with `JHtml::_('script', ...)`

## Common Pitfall
Don't manually add `<script>` tags in the template - Joomla 4.x requires using the WebAsset Manager for proper dependency management and CSP compliance.
