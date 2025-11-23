window.onload = function() {

    var pageTitle           = document.title;
    var attentionMessages   = [];
    var currentMessageIndex = 0;
    var meta                = document.querySelector('meta[name="browserTabMsg"]');
    
    // Get attention messages
    if (meta) {
      var metaContent = meta.getAttribute('content');
      try {
        // Try to parse as JSON array
        var parsedMessages = JSON.parse(metaContent);
        if (Array.isArray(parsedMessages) && parsedMessages.length > 0) {
          attentionMessages = parsedMessages;
        } else {
          // Fallback to single message
          attentionMessages = [metaContent];
        }
      } catch (e) {
        // If not valid JSON, treat as single message
        attentionMessages = [metaContent];
      }
    }

    // If no messages configured, use page title
    if (attentionMessages.length === 0) {
      attentionMessages = [pageTitle];
    }

    // Get return notification settings
    var returnEnabled = false;
    var returnMessage = 'Welcome back! 🎉';
    var returnDuration = 2000;
    
    var returnMsgMeta = document.querySelector('meta[name="browserTabReturnMsg"]');
    var returnDurationMeta = document.querySelector('meta[name="browserTabReturnDuration"]');
    
    if (returnMsgMeta) {
      returnEnabled = true;
      returnMessage = returnMsgMeta.getAttribute('content');
    }
    
    if (returnDurationMeta) {
      returnDuration = parseInt(returnDurationMeta.getAttribute('content'), 10) || 2000;
    }

    var blinkEvent = null;
    var rotationEvent = null;
    var returnTimeout = null;
    var currentDomain = window.location.hostname;
  
    document.addEventListener('visibilitychange', function(e) {
      var isPageActive = !document.hidden;
  
      if (!isPageActive) {
        blink();
      } else {
        stopBlinking();
        showReturnNotification();
      }
    });
  
    function blink() {
      var showMessage = true;
      
      // Blink current message
      blinkEvent = setInterval(function() {
        if (showMessage) {
          document.title = attentionMessages[currentMessageIndex];
        } else {
          document.title = pageTitle;
        }
        showMessage = !showMessage;
      }, 100);

      // Rotate through messages if multiple
      if (attentionMessages.length > 1) {
        rotationEvent = setInterval(function() {
          currentMessageIndex = (currentMessageIndex + 1) % attentionMessages.length;
        }, 3000); // Change message every 3 seconds
      }
    }
    
    function stopBlinking() {
      clearInterval(blinkEvent);
      clearInterval(rotationEvent);
      clearTimeout(returnTimeout);
      currentMessageIndex = 0;
    }
    
    function showReturnNotification() {
      if (returnEnabled) {
        // Show welcome back message
        document.title = returnMessage;
        
        // Restore original title after specified duration
        returnTimeout = setTimeout(function() {
          document.title = pageTitle;
        }, returnDuration);
      } else {
        // Immediately restore original title if return notification disabled
        document.title = pageTitle;
      }
    }
  
    window.addEventListener('blur', function() {
      // Save the current domain name when the user switches to a new tab
      currentDomain = window.location.hostname;
    });
  
    window.addEventListener('focus', function() {
      // Check if the user switched to a new site when the tab regained focus
      if (window.location.hostname !== currentDomain) {
        blink();
      } else {
        stopBlinking();
        showReturnNotification();
      }
    });
  };
  