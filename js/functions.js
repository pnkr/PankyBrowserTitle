window.onload = function() {

    var pageTitle           = document.title;
    var attentionMessage    = document.title;
    var meta                = document.querySelector('meta[name="browserTabMsg"]');
    
    if (meta) {
      var attentionMessage = meta.getAttribute('content');
    }

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
  