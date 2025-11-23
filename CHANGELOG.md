# Changelog

All notable changes to this project will be documented in this file.

## [2.0.0] - 2025-11-23

### Added
- **Multiple Message Rotation**: Configure multiple messages that rotate every 3 seconds while user is away
- **Return Notification**: Show customizable welcome-back message when user returns to tab
  - Configurable message text (supports emoji)
  - Configurable display duration (500-10000ms)
- **Modern Joomla 5/6 Architecture**:
  - Service provider with dependency injection
  - Event subscriber pattern
  - Namespace-based structure
  - PSR-4 autoloading
- Update server for automatic updates via Joomla Update System

### Changed
- Migrated from Joomla 4 structure to Joomla 5/6 modern architecture
- Updated plugin manifest to use namespace declaration
- Enhanced JavaScript with better message rotation logic
- Improved data flow with JSON encoding for messages
- Better cleanup of intervals and timeouts

### Technical
- PHP minimum version: 8.1.0
- Joomla target: 5.x and 6.x
- Uses modern WebAsset Manager
- Implements SubscriberInterface for event handling

## [1.0.1] - 2023-04

### Added
- Initial release for Joomla 4.x
- Single custom message when user switches tabs
- Blinking animation effect
- WebAsset integration

### Features
- Custom message configuration
- Automatic language loading
- Visibility API for tab detection
