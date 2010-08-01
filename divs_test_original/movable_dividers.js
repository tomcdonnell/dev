/**************************************************************************************************\
*
* Filename: "movable_dividers.js"
*
* Purpose: Functions and variables for implementing movable dividers
*          on a web page without resorting to the use of frames.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

/**
 * Movable dividers GUI element.
 */
function MovableDividers()
{
   // Priviliged Functions. /////////////////////////////////////////////////////////////////////

   /**
    * Initialise the movable dividers.
    */
   this.init = function ()
   {
      console.info('MovableDividers: Initialising.');

      onResize();

      h_Div.addEventListener('mousedown', onMouseDown, false);
      v_Div.addEventListener('mousedown', onMouseDown, false);

      window.addEventListener('resize', onResize, false);
   };

   // Private Functions. ////////////////////////////////////////////////////////////////////////

   // Event handlers. -------------------------------------------------------------------------//

   /**
    *
    */
   function onResize()
   {
      try
      {
         var f = 'MovableDividers.onResize()';
         UTILS.assert(f, 0, arguments.length <= 1); // Expect arguments to be [Event] or [].

         // Calculate the height and width of the screen area devoted to the three frames.
         var fullH = window.innerHeight - TITLE_BAR_HEIGHT - BOTTOM_BAR_HEIGHT;
         var fullW = window.innerWidth;

         // Calculate the h-divider height, v-divider width, and the divisible height/width.
         // The divisible height is the height to be divided between the top and bottom frames.
         // The top frame is divided into the top-left and top-right frames.  The divisible
         // width is the width to be divided between the top-left and top-right frames.
         // (If the divisible height/width is odd, dividing it in two will result
         //  in non-integer values.  Avoid non-integer values by decrementing
         //  the divisible height/width, and incrementing the divider height/width).
         h_DivH = IDEAL_H_DIVIDER_HEIGHT;
         v_DivW = IDEAL_V_DIVIDER_WIDTH;
         divisibleH = fullH - h_DivH;
         divisibleW = fullW - v_DivW;
         if (UTILS.math.isOdd(divisibleH)) {divisibleH--; h_DivH++;}
         if (UTILS.math.isOdd(divisibleW)) {divisibleW--; v_DivW++;}

         // Remember the top div height.
         t_DivH = divisibleH / 2;

         // Set the height style property of selected elements.
         var t_DivHstr = t_DivH + 'px';
         var h_DivHstr = h_DivH + 'px';
         var tlTCDHstr = t_DivH - TAB_HEADING_HEIGHT + 'px';
         tlDiv.style.height = t_DivHstr;
         tlTCD.style.height = tlTCDHstr;
         v_Div.style.height = t_DivHstr;
         trDiv.style.height = t_DivHstr;
         trTCD.style.height = tlTCDHstr;
         h_Div.style.height = h_DivHstr;
         b_Div.style.height = t_DivHstr;
         b_TCD.style.height = tlTCDHstr;

         // Set the width style property of selected elements.
         var t_DivWstr = fullW  + 'px';
         var tlDivW    = divisibleW / 2;
         var tlDivWstr = tlDivW + 'px';
         var v_DivWstr = v_DivW + 'px';
         tlDiv.style.width = tlDivWstr;
         v_Div.style.width = v_DivWstr;
         trDiv.style.width = tlDivWstr;
         h_Div.style.width = t_DivWstr;

         // NOTE: The properties set above are the only ones
         //       that will be changed in the event handlers.

         // Remember half dimensions and middle coordinates of the dividers.
         v_DivHalfW = Math.round(v_DivW / 2);
         h_DivHalfH = Math.round(h_DivH / 2);
         v_DivMX = tlDivW + v_DivHalfW;
         h_DivMY = TITLE_BAR_HEIGHT + t_DivH + h_DivHalfH;

         // Remember limits for dragging.
         var w = MIN_FRAME_WIDTH  + v_DivHalfW;
         var h = MIN_FRAME_HEIGHT + h_DivHalfH;
         minDragX =  w;
         maxDragX = -w + window.innerWidth;
         minDragY =  h + TITLE_BAR_HEIGHT;
         maxDragY = -h + window.innerHeight - BOTTOM_BAR_HEIGHT;
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseDown(e)
   {
      try
      {
         var f = 'MovableDividers.onMouseDown()';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         // Add event listeners.
         window.addEventListener('mousemove', onMouseMove, false);
         window.addEventListener('mouseout' , onMouseOut , false);
         window.addEventListener('mouseup'  , onMouseUp  , false);

         revealGhosts();

         switch (e.target)
         {
          case v_Div:
            vDividerIsBeingDragged = true;
            break;
          case h_Div:
            hDividerIsBeingDragged = true;
            grabbedHdividerOnLeft = (e.clientX < v_DivMX);
            break;
          default:
            throw new Exception(f, 'Default case reached in switch statement', '');
            break;
         }
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseMove(e)
   {
      try
      {
         var f = 'BottomDrawers.onMouseMove()';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         if (vDividerIsBeingDragged)
         {
            // Move the v-divider ghost.
            var x = e.clientX;
            if (x < minDragX) x = minDragX;
            if (x > maxDragX) x = maxDragX;
            ghostOne.style.left = x - v_DivHalfW + 'px';

            // Start dragging the h-divider if necessary.
            if (!hDividerIsBeingDragged) hDividerIsBeingDragged = (e.clientY > h_DivMY);
         }

         if (hDividerIsBeingDragged)
         {
            // Move the h-divider ghost and change the height of the v-divider ghost.
            var y = e.clientY;
            if (y < minDragY) y = minDragY;
            if (y > maxDragY) y = maxDragY;
            var t = y - h_DivHalfH;
            ghostTwo.style.top    = t + 'px';
            ghostOne.style.height = t - TITLE_BAR_HEIGHT + 'px';

            // Start dragging the v-divider if necessary.
            if (!vDividerIsBeingDragged)
            {
               if (grabbedHdividerOnLeft) vDividerIsBeingDragged = (e.clientX > v_DivMX);
               else                       vDividerIsBeingDragged = (e.clientX < v_DivMX);
            }
         }
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseOut(e)
   {
      try
      {
         var f = 'MovableDividers.onMouseOut()';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         if (e.relatedTarget == null || e.relatedTarget == HTML_DOM_ELEMENT)
         {
            onMouseUp(e);
         }
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onMouseUp(e)
   {
      try
      {
         var f = 'MovableDividers.onMouseUp()';
         UTILS.checkArgs(f, arguments, [MouseEvent]);

         // Remove event listeners.
         window.removeEventListener('mousemove', onMouseMove, false);
         window.removeEventListener('mouseout' , onMouseOut , false);
         window.removeEventListener('mouseup'  , onMouseUp  , false);

         if (vDividerIsBeingDragged)
         {
            vDividerIsBeingDragged = false;

            // Set the required widths.
            var tlDivW = UTILS.DOM.removePXsuffix(ghostOne.style.left);
            var trDivW = divisibleW - tlDivW;
            tlDiv.style.width = tlDivW + 'px';
            trDiv.style.width = trDivW + 'px';

            // Remember the x coordinate of the middle of the v-divider.
            v_DivMX = tlDivW + v_DivHalfW;
         }

         if (hDividerIsBeingDragged)
         {
            hDividerIsBeingDragged = false;

            // Remember the new height of the top frame.
            t_DivH = UTILS.DOM.removePXsuffix(ghostTwo.style.top) - TITLE_BAR_HEIGHT;

            // Set the required heights.
            var t_DivHstr = t_DivH + 'px';
            var tlTCDHstr = t_DivH - TAB_HEADING_HEIGHT + 'px';
            var b_DivH    = divisibleH - t_DivH;
            var b_DivHstr = b_DivH + 'px';
            var b_TCDHstr = b_DivH - TAB_HEADING_HEIGHT + 'px';
            tlDiv.style.height = t_DivHstr;
            tlTCD.style.height = tlTCDHstr;
            v_Div.style.height = t_DivHstr;
            trDiv.style.height = t_DivHstr;
            trTCD.style.height = tlTCDHstr;
            b_Div.style.height = b_DivHstr;
            b_TCD.style.height = b_TCDHstr;

            // Remember the y coordinate of the middle of the h-divider.
            h_DivMY = TITLE_BAR_HEIGHT + t_DivH + h_DivHalfH;
         }

         hideGhosts();
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Other functions. ------------------------------------------------------------------------//

   /**
    * Set the position and dimensions of ghostOne and ghostTwo to match
    * the v-divider and h-divider respectively, then reveal both ghosts.
    */
   function revealGhosts()
   {
      // Set the position and dimensions of ghostOne to match those of the v-divider.
      ghostOne.style.height = v_Div.style.height;
      ghostOne.style.width  = v_Div.style.width;
      ghostOne.style.bottom = 'auto';
      ghostOne.style.right  = 'auto';
      ghostOne.style.top    = TITLE_BAR_HEIGHT + 'px';
      ghostOne.style.left   = tlDiv.style.width;
      // PROBLEM: The line above causes a mousemove event to be fired
      //          when the mousedown event fires for the first time.

      // Set the position and dimensions of ghostTwo to match those of the h-divider.
      ghostTwo.style.height = h_Div.style.height;
      ghostTwo.style.width  = h_Div.style.width;
      ghostTwo.style.bottom = 'auto';
      ghostTwo.style.right  = 'auto';
      ghostTwo.style.left   = '0px';
      ghostTwo.style.top    = TITLE_BAR_HEIGHT + t_DivH + 'px';
      // PROBLEM: The line above causes a mousemove event to be fired
      //          when the mousedown event fires for the first time.

      // Reveal the ghost divs
      ghostOne.style.visibility = 'visible';
      ghostTwo.style.visibility = 'visible';
   }

   /**
    * Hide both ghosts.
    */
   function hideGhosts()
   {
      ghostOne.style.visibility = 'hidden';
      ghostTwo.style.visibility = 'hidden';
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var hDividerIsBeingDragged = false;
   var vDividerIsBeingDragged = false;

   // Boolean.  Whether the h-divider was grabbed on the left or right of the v-divider.
   var grabbedHdividerOnLeft;

   // Frame divs.
   var tlDiv = document.getElementById('topLeft' );
   var trDiv = document.getElementById('topRight');
   var b_Div = document.getElementById('bottom'  );

   // Tab content divs (TCD).
   var tlTCD = tlDiv.lastChild.previousSibling;
   var trTCD = trDiv.lastChild.previousSibling;
   var b_TCD = b_Div.lastChild.previousSibling;

   // Divider divs.
   var v_Div = document.getElementById('vDivider'); // Separates the top-left and top-right frames.
   var h_Div = document.getElementById('hDivider'); // Separates the top and bottom frames.

   // Ghosts (ghostOne used for v-divider, ghostTwo used for h-divider).
   var ghostOne = document.getElementById('ghostOne');
   var ghostTwo = document.getElementById('ghostTwo');

   // Divisible width and height (see onResize()).
   var divisibleW;
   var divisibleH;

   var h_DivH; // Actual horizontal divider height (See onResize()).
   var v_DivW; // Actual vertical   divider width  (See onResize()).

   // Divider half dimensions.
   var v_DivHalfW;
   var h_DivHalfH;

   // Divider middle coordinates.
   var v_DivMX;
   var h_DivMY;

   var t_DivH; // Set on mouseup event, so valid only when dividers are not being dragged.

   // Private constants. ////////////////////////////////////////////////////////////////////////

   const HTML_DOM_ELEMENT = document.getElementsByTagName('html')[0];

   const TITLE_BAR_HEIGHT = UTILS.DOM.getDimensionInPixels
   (
      document.getElementById('titleBar'), 'height'
   );

   const BOTTOM_BAR_HEIGHT = UTILS.DOM.getDimensionInPixels
   (
      document.getElementById('bottomBar'), 'height'
   );

   const TAB_HEADING_HEIGHT =
   (
      UTILS.DOM.getDimensionInPixels
      (
         document.getElementById('bottom').firstChild.nextSibling, 'height'
      )
   );

   const MIN_SCROLL_BAR_DIMENSION = 45; // Units: Pixels.

   const MIN_FRAME_HEIGHT = MIN_SCROLL_BAR_DIMENSION + TAB_HEADING_HEIGHT;
   const MIN_FRAME_WIDTH  = MIN_SCROLL_BAR_DIMENSION;

   const IDEAL_H_DIVIDER_HEIGHT = 10; // Units: Pixels.
   const IDEAL_V_DIVIDER_WIDTH  = 10; // Units: Pixels.

   // Inititialisation code. ////////////////////////////////////////////////////////////////////

   this.init();
}

/*******************************************END*OF*FILE********************************************/
