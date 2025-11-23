# Browser Title Notification Plugin for Joomla 5/6

This system plugin lets admins set custom messages that visitors see in the browser tab title when they switch away from the site. Perfect for re-engaging visitors and bringing them back!

## Features

### 🔄 Multiple Message Rotation (v2.0+)
- Configure multiple messages that rotate every 3 seconds
- Keep visitors engaged with varied attention-grabbing messages
- Example: "Come back! 👋" → "Don't leave yet!" → "We miss you!"

### 🎉 Return Notification (v2.0+)
- Show a welcome-back message when users return to your tab
- Fully customizable message text (emoji supported!)
- Configurable duration (500-10000ms)
- Example: "Welcome back! 🎉" displays for 2 seconds, then restores original title

### ⚡ Modern Architecture
- Built for Joomla 5.x and 6.x
- Uses modern dependency injection and event subscription
- Namespace-based PSR-4 autoloading
- Automatic updates via Joomla Update System

## Installation

1. Download the latest release from [GitHub Releases](https://github.com/pnkr/PankyBrowserTitle/releases)
2. Install via Joomla Extensions Manager
3. Enable the plugin in System → Plugins
4. Configure your messages and settings

## Configuration

### Single Message Mode
1. Select "Single Message" mode
2. Enter your custom message
3. Save

### Multiple Messages Mode
1. Select "Multiple Messages (Rotating)" mode
2. Add multiple messages (up to 10)
3. Messages will rotate every 3 seconds

### Return Notification
1. Enable "Return Notification"
2. Set your welcome-back message (default: "Welcome back! 🎉")
3. Set display duration in milliseconds (default: 2000ms)

## Automatic Updates

The plugin supports automatic updates through Joomla's Update System:
- Updates are checked via GitHub releases
- Update notifications appear in Joomla Update Manager
- One-click update from Extensions → Update

## Original Inspiration

This plugin is based on an original idea from this article: https://medium.com/@alperen.talaslioglu/changing-page-title-dynamically-when-user-changes-tab-5d372554377c

## Screenshots

![Configuration](https://user-images.githubusercontent.com/4727788/229352952-9177a45d-84a4-463f-a80c-1c817438e2c0.png)

![Example](https://user-images.githubusercontent.com/4727788/229353104-d624de34-0c64-481e-9266-bec584359b56.png)

## Requirements

- **Joomla:** 5.x or 6.x
- **PHP:** 8.1.0 or higher

## License

GNU General Public License version 3 or later

## Author

Panayiotis Kiriakopoulos - https://github.com/pnkr
