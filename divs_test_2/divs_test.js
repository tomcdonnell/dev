/**************************************************************************************************\
*
* Filename: "divs_test.js"
*
* Purpose: Javascripts for the file "divs_test.php".
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

window.addEventListener('load', onLoadWindow, false);

/*
 * Program start point.
 */
function onLoadWindow(e)
{
   try
   {
      var f = 'onLoadWindow()';
      UTILS.checkArgs(f, arguments, [Event]);

      DomBuilder.apply(window);

      var body               = document.getElementsByTagName('body')[0];
      var framesContainerDiv = DIV();

      function onResizeWindow(e)
      {
         //framesContainerDiv.style.position = 'absolute';
         //framesContainerDiv.style.top      = '100px';
         //framesContainerDiv.style.left     = '200px';
         framesContainerDiv.style.width    = window.innerWidth  /*/ 2*/ + 'px';
         framesContainerDiv.style.height   = window.innerHeight /*/ 2*/ + 'px';
      }

      window.addEventListener('resize', onResizeWindow, false);
      onResizeWindow();

      body.style.overflow = 'hidden';

      var layout = new ThreeFrameLayoutWithMovableDividers(framesContainerDiv);

      var tlLayout = new ThreeFrameLayoutWithMovableDividers(layout.getTopLeftFrameDiv());
      layout.subscribeChildResizeFunction(tlLayout.onResize);
      

      var trFrame = layout.getTopRightFrameDiv();

      trFrame.appendChild(H1('Frame Content Test'));
      trFrame.appendChild(P('Abcdefg hijk lm nopq rstuvqzyx.  Abc def, ghij klmn opqr.'));

      body.appendChild(framesContainerDiv);
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}



/*******************************************END*OF*FILE********************************************/
