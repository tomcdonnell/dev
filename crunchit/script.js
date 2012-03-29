/*
 * vim: ts=2 sw=2 et wrap co=100 go-=b
 */

/* Author: Ben Pitt [Shattered Multimedia] */
jQuery(document).ready(function () {
  // Hide all children of archive months
  jQuery('#news_archives > ul > li         > ul').hide();
  jQuery('#news_archives > ul > li.current > ul').show();

  /* Start section completed by Tom McDonnell [tomcdonnell.net] */

  var latestNewsCopyIsDisplayed     = false;
  var onHoverLatestNewsCopyIsActive = false;

  jQuery('.latestNewsCarousel > ul > li').hover(
    function (ev) {
      var liElemJq       = jQuery(this);
      var latestNewsCopy = liElemJq.find('p').html();
      var pElemJq        = getLatestNewsCopyParagraphElementJq();

      deactivateAllButOneLatestNewsCopyHeadings(liElemJq);
      pElemJq.html(latestNewsCopy);

      if (!latestNewsCopyIsDisplayed) {
        displayLatestNewsCopy(true, liElemJq);
      }
    },
    function (ev) {
      if (
        mouseIsOverElement(ev, jQuery('.latestNewsCopy'    )) ||
        mouseIsOverElement(ev, jQuery('#homepageLatestNews'))
      ) {
        onHoverLatestNewsCopyIsActive = true;
        return;
      }

      displayLatestNewsCopy(false, null);
    }
  );

  jQuery('.latestNewsCopy').mouseleave(
    function (ev) {
       if (!onHoverLatestNewsCopyIsActive) {
         return;
       }

       if (!mouseIsOverElement(ev, jQuery('#homepageLatestNews'))) {
         displayLatestNewsCopy(false, null);
         onHoverLatestNewsCopyIsActive = false;
       }
    }
  );

  function displayLatestNewsCopy(bool, liElemJq) {
    var pElemJq = getLatestNewsCopyParagraphElementJq();

    if (bool) {
      pElemJq.slideDown();
      latestNewsCopyIsDisplayed = true;
    }
    else {
      pElemJq.slideUp();
      deactivateAllButOneLatestNewsCopyHeadings(null);
      latestNewsCopyIsDisplayed = false;
    }
  }

  function mouseIsOverElement(ev, elementJq) {
    var mouseX = ev.clientX;
    var mouseY = ev.clientY;
    var offset = elementJq.offset();
    var width  = elementJq.width();
    var height = elementJq.height();

    return (
      mouseY >= offset.top  && mouseY <= offset.top  + height &&
      mouseX >= offset.left && mouseX <= offset.left + width
    );
  }

  function getLatestNewsCopyParagraphElementJq() {
    return jQuery('section.homeTemplateThree > p.latestNewsCopy');
  }

  function deactivateAllButOneLatestNewsCopyHeadings(liElemJqOrNull) {
    jQuery('.latestNewsCarousel > ul > li').removeClass('latestNewsCopyHeadingActive');

    if (liElemJqOrNull !== null) {
      liElemJqOrNull.addClass('latestNewsCopyHeadingActive');
    }
  }

  /* End section completed by Tom McDonnell [tomcdonnell.net] */

  // jCarousellite [latest news]
  jQuery(function () {
    jQuery(".latestNewsCarousel").jCarouselLite({
      btnNext : "#nextLatestNews",
      btnPrev : "#previousLatestNews",
      circular: false,
      speed   : 1200,
      visible : 3,
      scroll  : 3
    });
  });

  // Superfish [main navigation dropdowns]
  jQuery('ul.sf-menu').superfish({
    delay      : 400,                            // one second delay on mouseout
    animation  : {opacity:'show',height:'show'}, // fade-in and slide-down animation
    speed      : 'medium',                       // faster animation speed
    autoArrows : false,                          // disable generation of arrow mark-up
    dropShadows: false                           // disable drop shadows
  });

  jQuery('#news_archives > ul > li > a').click(function (ev) {
    jQuery(ev.target.parentNode.children[1]).slideToggle();
    return false;
  });
  // FIX FOUT - Paul Irish - flash of unstyled text
  (function () {
    // if firefox 3.5+, hide content till load (or 3 seconds) to prevent FOUT
    var d = document, e = d.documentElement, s = d.createElement('style');
    if (e.style.MozTransform === ''){ // gecko 1.9.1 inference
      s.textContent = 'body{visibility:hidden}';
      var r = document.getElementsByTagName('script')[0];
      r.parentNode.insertBefore(s, r);
      function f() {s.parentNode && s.parentNode.removeChild(s);}
      addEventListener('load', f, false);
      setTimeout(f, 3000);
    }
  })();
});
