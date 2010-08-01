/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "FormSection2.js"
*
* Project: Performance Management.
*
* Purpose: Javascript pertaining to section 2 of the form.
*
* Author: Tom McDonnell 2010-07-21.
*
\**************************************************************************************************/

// Object definition. //////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function FormSection2()
{
   var f = 'FormSection2()';
   UTILS.checkArgs(f, arguments, []);

   // Private functions. ////////////////////////////////////////////////////////////////////////

   // Event listeners. ------------------------------------------------------------------------//

   /*
    *
    */
   function onChangeCapLevel(e)
   {
      try
      {
         var f = 'FormSection2.onChangeCapLevel()';
         UTILS.checkArgs(f, arguments, [Object]);

         var capNameTd = e.target.parentNode.previousSibling;

         // Skip textnode if necessary.
         if (capNameTd.nodeType == 3)
         {
            capNameTd = capNameTd.previousSibling;
         }

         var rowNos = getTableTwoRowNumbersWithSelectedCapName(capNameTd.innerHTML);

         for (var i = 0; i < rowNos.length; ++i)
         {
            updateInputsForRowNo(rowNos[i]);
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
   function onChangeCapName(e)
   {
      try
      {
         var f = 'FormSection2.onChangeCapName()';
         UTILS.checkArgs(f, arguments, [Object]);

         var selector      = $(e.target);
         var selectorName  = $(selector).attr('name');
         var selectedIndex = selector.selectedIndex;
         var rowNo         = Number(selectorName.substr(selectorName.length - 1));

         if (selectedIndex != 0)
         {
            disableOptionInAllOtherCapNameSelectors(selectedIndex, rowNo);
         }

         updateInputsForRowNo(rowNo);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function onChangeRequiredCapLevel(e)
   {
      try
      {
         var f = 'FormSection2.onChangeRequiredCapLevel()';
         UTILS.checkArgs(f, arguments, [Object]);

         var selectorName = $(e.target).attr('name');
         var rowNo        = Number(selectorName.substr(selectorName.length - 1));

         updateInputsForRowNo(rowNo);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Getters. --------------------------------------------------------------------------------//

   /*
    *
    */
   function getCapLevelIndexForCapName(capName)
   {
      var f = 'FormSection2.getCapLevelIndexForCapName()';
      UTILS.checkArgs(f, arguments, [String]);

      var capLevelSelectors = inputs.selectors.capLevel;

      for (var i = 0; i < capLevelSelectors.length; ++i)
      {
         var selector  = capLevelSelectors[i];
         var td        = selector.parentNode;
         var capNameTd = td.previousSibling;

         // Skip textnode if necessary.
         if (capNameTd.nodeType == 3)
         {
            capNameTd = capNameTd.previousSibling;
         }

         if (capNameTd.innerHTML == capName)
         {
            return Number(selector.selectedIndex);
         }
      }

      throw new Exception('No cap level selector found for cap name "' + capName + '".');
   }

   /*
    *
    */
   function getTableTwoRowNumbersWithSelectedCapName(capName)
   {
      var f = 'FormSection2.getTableTwoRowNumbersWithSelectedCapName()';
      UTILS.checkArgs(f, arguments, [String]);

      var capNameSelectors = inputs.selectors.capName;
      var rowNos           = [];

      for (var i = 0; i < capNameSelectors.length; ++i)
      {
         var capNameSelector    = capNameSelectors[i];
         var selectedOptionText = capNameSelector.options[capNameSelector.selectedIndex].innerHTML;

         if (selectedOptionText == capName)
         {
            rowNos.push(i + 1);
         }
      }

      return rowNos;
   }

   // Setters. --------------------------------------------------------------------------------//

   /*
    *
    */
   function setDisabledPersonalCapLevelForRowNo(optionIndex, rowNo)
   {
      var f = 'FormSection2.setDisabledPersonalCapLevelForRowNo()';
      UTILS.checkArgs(f, arguments, [Number, Number]);

      inputs.selectors.capLevelDisabled[rowNo - 1].selectedIndex = optionIndex;
   }

   /*
    *
    */
   function setRequiredCapLevelForRowNo(optionIndex, rowNo)
   {
      var f = 'FormSection2.setRequiredCapLevelForRowNo()';
      UTILS.checkArgs(f, arguments, [Number, Number]);

      inputs.selectors.capLevelRequired[rowNo - 1].selectedIndex = optionIndex;
   }

   /*
    *
    */
   function setDevelopmentRequiredForRowNo(rowNo)
   {
      var f = 'FormSection2.setDevelopmentRequiredForRowNo()';
      UTILS.checkArgs(f, arguments, [Number]);   

      var selectors                = inputs.selectors;
      var radioButtons             = inputs.radioButtons;
      var capLevelRequiredSelector = selectors.capLevelRequired[rowNo - 1];
      var capLevelDisabledSelector = selectors.capLevelDisabled[rowNo - 1];
      var bool                     =
      (
         (capLevelRequiredSelector.selectedIndex == 0)? false:
         (capLevelRequiredSelector.selectedIndex > capLevelDisabledSelector.selectedIndex)
      );

      radioButtons.devRequiredY[rowNo - 1].checked =  bool;
      radioButtons.devRequiredN[rowNo - 1].checked = !bool;
   }

   // Other private functions. ----------------------------------------------------------------//

   /*
    *
    */
   function disableOptionInAllOtherCapNameSelectors(selectedIndex, rowNo)
   {
      var f = 'FormSection2.disableOptionInAllOtherCapNameSelectors()';
      UTILS.checkArgs(f, arguments, [Number, Number]);

      var selectors = inputs.selectors.capName;

      for (var i = 0; i < selectors.length; ++i)
      {
         if (i != rowNo - 1)
         {
            selector.options[selectedIndex].disabled = true;
         }
      }
   }

   /*
    *
    */
   function updateInputsForRowNo(rowNo)
   {
      var f = 'FormSection2.updateInputsForRowNo()';
console.debug(f, 'rowNo: ', rowNo);
      UTILS.checkArgs(f, arguments, [Number]);

      var selectors                = inputs.selectors;
      var capNameSelector          = selectors.capName[rowNo - 1];
      var capLevelRequiredSelector = selectors.capLevelRequired[rowNo - 1];
      var capLevelDisabledSelector = selectors.capLevelDisabled[rowNo - 1];
      var capName = capNameSelector.options[capNameSelector.selectedIndex].innerHTML;

      if (capName == '')
      {
         setRequiredCapLevelForRowNo(0, rowNo);
         setDisabledPersonalCapLevelForRowNo(0, rowNo);
      }
      else
      {
         setDisabledPersonalCapLevelForRowNo(getCapLevelIndexForCapName(capName), rowNo);
      }

      setDevelopmentRequiredForRowNo(rowNo);
   }

   /*
    *
    */
   function compareElementNameAttributes(htmlElementA, htmlElementB)
   {
      var f = 'FormSection2.compareElementNameAttributes()';

      // NOTE
      // ----
      // No type checking done here because different HTML element types (eg. HTMLSelectElement,
      // HTMLInputElement) do not seem to have a descendent type in common.

      var nameA = $(htmlElementA).attr('name');
      var nameB = $(htmlElementB).attr('name');

      return (nameA == nameB)? 0: ((nameA > nameB)? 1: -1);
   }

   /*
    *
    */
   function init()
   {
      var f = 'FormSection2.init()';
      UTILS.checkArgs(f, arguments, []);

      var selectors                 = inputs.selectors;
      var radioButtons              = inputs.radioButtons;
      var capLevelSelectors         = selectors.capLevel;
      var capLevelDisabledSelectors = selectors.capLevelDisabled;
      var capLevelRequiredSelectors = selectors.capLevelRequired;
      var capNameSelectors          = selectors.capName;

      capLevelSelectors.sort(compareElementNameAttributes);
      capLevelDisabledSelectors.sort(compareElementNameAttributes);
      capLevelRequiredSelectors.sort(compareElementNameAttributes);
      capNameSelectors.sort(compareElementNameAttributes);

      radioButtons.devRequiredY.sort(compareElementNameAttributes);
      radioButtons.devRequiredN.sort(compareElementNameAttributes);

      for (var i = 0; i < capLevelSelectors.length; ++i)
      {
         $(capLevelSelectors[i]).change(onChangeCapLevel);
      }

      for (var i = 0; i < capLevelRequiredSelectors.length; ++i)
      {
         $(capLevelRequiredSelectors[i]).change(onChangeRequiredCapLevel);
      }

      for (var i = 0; i < capNameSelectors.length; ++i)
      {
         $(capNameSelectors[i]).change(onChangeCapName);
      }
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var inputs =
   {
      selectors:
      {
         capLevel        : $('[name^=s2_capLevel_]'         ),
         capLevelDisabled: $('[name^=s2_capLevelPossessed_]'),
         capLevelRequired: $('[name^=s2_capLevelRequired_]' ),
         capName         : $('[name^=s2_capNameSelected_]'  )
      },
      radioButtons:
      {
         devRequiredY: $('[id^=s2_developmentRequiredY]'),
         devRequiredN: $('[id^=s2_developmentRequiredN]')
      }
   };

   // Initialisation code. //////////////////////////////////////////////////////////////////////

   init();
}

/*******************************************END*OF*FILE********************************************/
