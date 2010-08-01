/**************************************************************************************************\
*
* Filename: "divs_test.js"
*
* Purpose: Javascripts for the file "divs_test.php".
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

window.addEventListener('load', init, false);

/*
 * Program start point.
 */
function init()
{
   try
   {
      var f = 'init()';

      console.info('Initialising Javascripts.');

      // Create objects.
      movableDividers = new MovableDividers();
      //bottomDrawer    = new BottomDrawer();

      DomBuilder.apply(window);

      createModule();
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*
 *
 */
function createModule()
{
   UTILS.DOM.replaceElement
   (
      document.getElementById('testModuleDiv'),

      DIV
      (
         SPAN({style: 'display: block; width: 50%; text-align: right;'}, 'Question text?'),

         INPUT({style: 'float: right;', type: 'textbox'}),

         DIV({style: 'clear: both;'})
      )
   );
}

/*******************************************END*OF*FILE********************************************/
