/*
 * vim: ts=3 sw=3 et wrap co=100 go+=b
 */

$(document).ready
(
   function (ev)
   {
      $('#locations_list > li').click(onClickLocationLi);
   }
);

/*
 *
 */
function onClickLocationLi(ev)
{
   var li           = $(ev.target).parent();
   var locationNo   = $(li).index();
   var locationDivs = $('#locations_text').children();
   var mapDiv       = $('#map_div')[0];

   $(mapDiv).html(mapHtmls[locationNo]);

   for (var i = 0, len = locationDivs.length; i < len; ++i)
   {
      var locationDiv = locationDivs[i];

      if (i == locationNo)
      {
         $(locationDiv).show();
      }
      else
      {
         $(locationDiv).hide();

      }
   }
}

// Note Regarding Storage of Entire Iframe HTML
// --------------------------------------------
// Only the src attributes should need to be stored here.  The HTML for the entire iframe is stored
// because when I attempted to change only the src attribute, google for some reason displayed the
// entire google maps page, not just the map.  Try cutting and pasting a src url from one of the
// below iframes into a browser to see for yourself.
var mapHtmls =
[
   // Melbourne.
   '<iframe id="mapInlineFrame" width="500" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Melbourne,+VIC,+Australia&amp;aq=&amp;sll=-27.470963,153.0235&amp;sspn=0.057419,0.057936&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Melbourne+Victoria,+Australia&amp;t=h&amp;z=13&amp;ll=-37.813187,144.96298&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Melbourne,+VIC,+Australia&amp;aq=&amp;sll=-27.470963,153.0235&amp;sspn=0.057419,0.057936&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Melbourne+Victoria,+Australia&amp;t=h&amp;z=13&amp;ll=-37.813187,144.96298" style="color:#0000FF;text-align:left">View Larger Map</a></small>',

   // Sydney.
   '<iframe id="mapInlineFrame" width="500" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Sydney,+NSW,+Australia&amp;aq=&amp;sll=-37.813187,144.96298&amp;sspn=0.102254,0.115871&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Sydney+New+South+Wales,+Australia&amp;t=h&amp;z=14&amp;ll=-33.873651,151.20689&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Sydney,+NSW,+Australia&amp;aq=&amp;sll=-37.813187,144.96298&amp;sspn=0.102254,0.115871&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Sydney+New+South+Wales,+Australia&amp;t=h&amp;z=14&amp;ll=-33.873651,151.20689" style="color:#0000FF;text-align:left">View Larger Map</a></small>',

   // Brisbane.
   '<iframe id="mapInlineFrame" width="500" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Brisbane,+QLD,+Australia&amp;aq=0&amp;sll=-31.952854,115.857339&amp;sspn=0.109821,0.115871&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Brisbane+Queensland,+Australia&amp;ll=-27.470933,153.023502&amp;spn=0.057419,0.057936&amp;t=h&amp;z=14&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Brisbane,+QLD,+Australia&amp;aq=0&amp;sll=-31.952854,115.857339&amp;sspn=0.109821,0.115871&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Brisbane+Queensland,+Australia&amp;ll=-27.470933,153.023502&amp;spn=0.057419,0.057936&amp;t=h&amp;z=14" style="color:#0000FF;text-align:left">View Larger Map</a></small>',

   // Gold-Coast.
   '<iframe id="mapInlineFrame" width="500" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Gold+Coast,+QLD,+Australia&amp;aq=&amp;sll=-33.873651,151.20689&amp;sspn=0.053732,0.057936&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Gold+Coast,+Queensland,+Australia&amp;t=h&amp;z=10&amp;ll=-28.041014,153.297505&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Gold+Coast,+QLD,+Australia&amp;aq=&amp;sll=-33.873651,151.20689&amp;sspn=0.053732,0.057936&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Gold+Coast,+Queensland,+Australia&amp;t=h&amp;z=10&amp;ll=-28.041014,153.297505" style="color:#0000FF;text-align:left">View Larger Map</a></small>',

   // Perth.
   '<iframe id="mapInlineFrame" width="500" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Perth,+WA,+Australia&amp;aq=&amp;sll=-28.041014,153.297505&amp;sspn=0.913908,0.926971&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Perth+Western+Australia,+Australia&amp;t=h&amp;z=13&amp;ll=-31.952854,115.857339&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Perth,+WA,+Australia&amp;aq=&amp;sll=-28.041014,153.297505&amp;sspn=0.913908,0.926971&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Perth+Western+Australia,+Australia&amp;t=h&amp;z=13&amp;ll=-31.952854,115.857339" style="color:#0000FF;text-align:left">View Larger Map</a></small>'
];
