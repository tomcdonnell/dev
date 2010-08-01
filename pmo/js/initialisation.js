/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "initialisation.js"
*
* Project: Performance Management.
*
* Purpose: Starting point for the Javascript code.
*
* Author: Tom McDonnell 2010-07-23.
*
\**************************************************************************************************/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $(document).ready(onLoadDocument);
}
catch (e)
{
   console.debug('initialisation.js', e);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function onLoadDocument(e)
{
   try
   {
      var f = 'onLoadDocument()';
      UTILS.checkArgs(f, arguments, [Function]);

      if (typeof(window.loadFirebugConsole) != 'undefined')
      {
         window.loadFirebugConsole();
      }

      $(window).resize(onResizeWindow);
      $(window).trigger('resize');

      var formGlobal   = new FormGlobal(onResizeWindow);
      var formSection2 = new FormSection2();
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*
 *
 */
function onResizeWindow(e)
{
   try
   {
      var f = 'onResizeWindow()';
      UTILS.checkArgs(f, arguments, [Object]);

      $('#layoutMain'    ).css('height', 'auto');
      $('#navBackground1').css('height', 'auto');

      var mainSectionWidth  = $(window).width() - $('#navBackground1').outerWidth();
      var mainSectionHeight =
      (
         Math.max
         (
            $('#layoutMain'    ).outerHeight(),
            $('#navBackground1').outerHeight(),
            (
               $(window).height()
               -  $('#header'    ).outerHeight() - $('#layoutFooter').outerHeight()
               - ($('#layoutMain').outerHeight() - $('#layoutMain'  ).innerHeight())
            )
         )
      );

      $('#layoutMain'    ).css('height', String(mainSectionHeight) + 'px');
      $('#navBackground1').css('height', String(mainSectionHeight) + 'px');
      $('#layoutMain'    ).css('width' , String(mainSectionWidth ) + 'px');
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*******************************************END*OF*FILE********************************************/
