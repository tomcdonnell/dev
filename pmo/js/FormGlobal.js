/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "FormGlobal.js"
*
* Project: Performance Management.
*
* Purpose: Javascript pertaining to the entire form.
*
* Author: Tom McDonnell 2010-07-21.
*
\**************************************************************************************************/

// Object definition. //////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function FormGlobal(onResizeWindow)
{
   var f = 'FormGlobal()';
   UTILS.checkArgs(f, arguments, [Function]);

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   function init()
   {
      var f = 'FormGlobal.init()';
      UTILS.checkArgs(f, arguments, []);

      for (var minusDivId in idsToHideByMinusDivId)
      {
         var plusDivId = minusDivId.replace('Minus', 'Plus');

         $('#' + minusDivId).click(onClickMinus);
         $('#' + plusDivId ).click(onClickPlus );
      }
   }

   // Event listeners. ------------------------------------------------------------------------//

   /*
    *
    */
   function onClickMinus(e)
   {
      try
      {
         var f = 'FormGlobal.onClickMinus()';
         //UTILS.checkArgs(f, arguments, [Event]);

         e.preventDefault();

         var minusDiv   = e.target.parentNode.parentNode;
         var minusDivId = $(minusDiv).attr('id');
         var plusDivId  = minusDivId.replace('Minus', 'Plus');
         var plusDiv    = $('#' + plusDivId);

         $(minusDiv).css('display', 'none'  );
         $(plusDiv ).css('display', 'inline');

         var idsToHide = idsToHideByMinusDivId[$(minusDiv).attr('id')];

         for (var i = 0; i < idsToHide.length; ++i)
         {
            $('#' + idsToHide[i]).css('display', 'none')
         }

         onResizeWindow(e);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onClickPlus(e)
   {
      try
      {
         var f = 'FormGlobal.onClickPlus()';
         //UTILS.checkArgs(f, arguments, [Event]);

         e.preventDefault();

         var plusDiv    = e.target.parentNode.parentNode;
         var plusDivId  = $(plusDiv).attr('id');
         var minusDivId = plusDivId.replace('Plus', 'Minus');
         var minusDiv   = $('#' + minusDivId);

         $(plusDiv ).css('display', 'none'  );
         $(minusDiv).css('display', 'inline');

         var idsToShow = idsToHideByMinusDivId[$(minusDiv).attr('id')];

         for (var i = 0; i < idsToShow.length; ++i)
         {
            $('#' + idsToShow[i]).css('display', 'block')
         }

         onResizeWindow(e);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var idsToHideByMinusDivId =
   {
      PSMinus1                   : ['PerformanceStandard1'                   ],
      PSMinusCapabilityAssessment: ['PerformanceStandardCapabilityAssessment'],
      PSMinus2                   : ['PerformanceStandard2', 'justification'  ],
      PSMinusCareerPlanning      : ['PerformanceStandardCareerPlanning'      ],
      PSMinus3                   : ['PerformanceStandard3'                   ],
      PSMinusGeneralComments     : ['PerformanceStandardGeneralComments'     ],
      PSMinusRevisionHistory     : ['PerformanceStandardRevisionHistory'     ]
   };

   // Initialisation code. //////////////////////////////////////////////////////////////////////

   init();
}

/*******************************************END*OF*FILE********************************************/
