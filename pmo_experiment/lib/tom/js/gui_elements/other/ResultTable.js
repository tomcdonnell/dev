/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "ResultTable.js"
*
* Project: GUI elements.
*
* Purpose: Definition of the ResultTable object.
*
* See also: /lib/tom/php/classes/ResultTableAjaxHelperAjax.php
*           /lib/tom/php/ajax/result_table_ajax.php
*
* Author: Tom McDonnell 2008-02-21.
*
\**************************************************************************************************/

/*
 *
 */
function ResultTable(ajaxUrl, initialClassClientAjaxParams, configParams)
{
   var f = 'ResultTable()';
   UTILS.checkArgs(f, arguments, [String, Object, Object]);

   // Priviliged functions. /////////////////////////////////////////////////////////////////////

   // Getters. --------------------------------------------------------------------------------//

   this.getTable             = function () {return _domElements.table            ;};
   this.getCreateNewRowTable = function () {return _domElements.createNewRowTable;};

   // Setters. --------------------------------------------------------------------------------//

   /*
    *
    */
   this.setDisabledForAllInputs = function (bool)
   {
      var f = 'ResultTable.setDisabledForAllInputs()';
      UTILS.checkArgs(f, arguments, [Boolean]);

      var sortButtonPairs = _inputs.sortButtonPairs;

      for (var i = 0, len = sortButtonPairs.length; i < len; ++i)
      {
         sortButtonPair = sortButtonPairs[i];
         sortButtonPair.asc.disabled = bool;
         sortButtonPair.dsc.disabled = bool;
      }

      // Set disabled for all buttons in the Actions column.
      var trs = $(_domElements.tbody).children();
      for (var i = 0, len = trs.length; i < len; ++i)
      {
         var jqInputs = $(trs[i]).find('td.actionsTd input');
         jqInputs.attr('disabled', bool);

         switch (bool)
         {
          case true : jqInputs.addClass('disabled'); break;
          case false: jqInputs.removeClass('disabled'); break;
         }
      }

      _setDisabledForPrevAndNextPageButtons(bool, bool);
   };

   // Other privileged functions. -------------------------------------------------------------//

   /*
    *
    */
   this.update = function (nextRequestClassClientParams)
   {
      var f = 'ResultTable.update()';
      UTILS.checkArgs(f, arguments, [Object]);

      _state.nextRequestClassClientParams = nextRequestClassClientParams;
      _initNextRequestResultTableParams();
      _requestDataFromServerViaAjax();
   };

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   function _requestDataFromServerViaAjax()
   {
      var f = 'ResultTable._requestDataFromServerViaAjax()';
      UTILS.checkArgs(f, arguments, []);

      _state.ajaxParams.data = JSON.stringify
      (
         {
            action: 'getData',
            params:
            {
               classClientParams: _state.nextRequestClassClientParams,
               resultTableParams: _state.nextRequestResultTableParams
            }
         }
      );

      self.setDisabledForAllInputs(true);
      $.ajax(_state.ajaxParams);
   }

   // Event listeners. ------------------------------------------------------------------------//

   /*
    *
    */
   function _onClickPrevOrNextButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickPrevOrNextButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         // Determine whether 'prev' or 'next' was clicked.
         switch (e.target)
         {
          case _inputs.prevPageButton: var nextClicked = false; break;
          case _inputs.nextPageButton: var nextClicked = true ; break;
          default: throw new Exception(f, 'Unexpected e.target.', '');
         }

         // Update nextRequestResultTableParams.offset.
         var p              = _state.returnedAjaxParams;
         var maxRowsPerPage = p.maxRowsPerPage;
         var n_rowsTotal    = p.n_rowsTotal;
         var newOffset      = p.offset + maxRowsPerPage * ((nextClicked)? 1: -1);
         if (newOffset < 0                           ) {newOffset = 0;                           }
         if (newOffset > n_rowsTotal - maxRowsPerPage) {newOffset = n_rowsTotal - maxRowsPerPage;}

         _state.nextRequestResultTableParams.offset = newOffset;
         _requestDataFromServerViaAjax();
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function _onClickSortButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickSortButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         // Determine whether 'asc' or 'dsc' was clicked.
         switch (UTILS.DOM.countPreviousSiblings(e.target))
         {
          case 0: var ascORdesc = 'asc' ; break;
          case 1: var ascORdesc = 'desc'; break;
          default: throw new Exception(f, "Neither 'asc' nor 'dsc' clicked.", '');
         }

         // Determine which column contains the button clicked.
         var colIndex = UTILS.DOM.countPreviousSiblings(e.target.parentNode);
         UTILS.assert(f, 0, 0 <= colIndex && colIndex < _state.n_cols);

         _state.nextRequestResultTableParams.orderByInfo = [[colIndex, ascORdesc]];
         _requestDataFromServerViaAjax();
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    * Create a new tr element containing editable cells, positioned above the tr element
    * on which it is based.  Hide the tr element on which the editable tr element is based.
    */
   function _onClickEditRowButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickEditRowButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         var editInfo   = _state.editInfo;
         var originalTr = $(e.currentTarget).parent().parent()[0];

         if (editInfo === null)
         {
            throw new Exception
            (
               f, 'Edit button clicked but should not exist since editInfo is null.', ''
            );
         }

         var editableTr = _createEditableTrFromOriginalTr(originalTr);

         $(originalTr).before(editableTr);
         $(originalTr).hide();
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    * Remove the TR element containing the button clicked, and reveal the next TR
    * element, which is assumed to be the uneditable version of the TR element removed.
    */
   function _onClickRevertRowButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickRevertRowButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         var editableTr = $(e.currentTarget).parent().parent()[0];
         $(editableTr).next().show();
         $(editableTr).remove();
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function _onClickSaveRowButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickSaveRowButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         var p                     = _state.returnedAjaxParams;
         var tr                    = $(e.currentTarget).parent().parent();
         var rowIndex              = $(tr).index();
         var trChildren            = $(tr).children();
         var rowValueByColumnIndex = [];

         // For each column except the last column (the Actions column)...
         for (var colIndex = 0; colIndex < trChildren.length - 1; ++colIndex)
         {
            rowValueByColumnIndex.push
            (
               (p.colInfoByColIndex[colIndex].isEditable)?
               $(trChildren[colIndex]).find('input').attr('value'): null
            );
         }

         _state.ajaxParams.data = JSON.stringify
         (
            {
               action: 'updateRow',
               params:
               {
                  classClientParams: _state.nextRequestClassClientParams  ,
                  resultTableParams: _state.nextRequestResultTableParams  ,
                  rowId            : p.rowInfoByRowIndex[rowIndex].data.id,
                  valueByColIndex  : rowValueByColumnIndex
               }
            }
         );

         _state.nextSuccessMsg = 'Row updated';
         self.setDisabledForAllInputs(true);
         $.ajax(_state.ajaxParams);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function _onClickCreateRowButton(e)
   {
      try
      {
         var f = 'ResultTable._onClickCreateRowButton()';
         UTILS.checkArgs(f, arguments, [Object]);

         var tr                    = $(e.currentTarget).parent().parent();
         var trChildren            = $(tr).children();
         var colInfoByColIndex     = _state.returnedAjaxParams.colInfoByColIndex;
         var rowValueByColumnIndex = [];
         var editableColumnIndex   = 0;

         // For each column except the last column (the Actions column)...
         for (var colIndex = 0; colIndex < trChildren.length - 1; ++colIndex)
         {
            if (colInfoByColIndex[colIndex].isEditable)
            {
               rowValueByColumnIndex.push
               (
                  $(trChildren[editableColumnIndex]).find('input').attr('value')
               );

               ++editableColumnIndex;
            }
            else
            {
               // Send a null value for each of the uneditable columns.
               rowValueByColumnIndex.push(null);
            }
         }

         _state.ajaxParams.data = JSON.stringify
         (
            {
               action: 'insertRow',
               params:
               {
                  classClientParams: _state.nextRequestClassClientParams,
                  resultTableParams: _state.nextRequestResultTableParams,
                  rowId            : ''                                 ,
                  valueByColIndex  : rowValueByColumnIndex
               }
            }
         );

         self.setDisabledForAllInputs(true);
         $.ajax(_state.ajaxParams);
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   /*
    *
    */
   function _onClickCustomButtonDefault(e)
   {
      try
      {
         var f = 'ResultTable._onClickCustomButtonDefault()';
         UTILS.checkArgs(f, arguments, [Object]);

         var p                     = _state.returnedAjaxParams;
         var rowInfoByRowIndex     = p.rowInfoByRowIndex;
         var clickedButton         = e.currentTarget;
         var tr                    = $(clickedButton).parent().parent();
         var rowIndex              = $(tr).index();
         var rowInfo               = rowInfoByRowIndex[rowIndex];
         var clickedButtonIndex    = _getButtonIndexForButton(clickedButton);
         var clickedButtonInfo     = rowInfo.buttonsInfo[clickedButtonIndex];
         var trChildren            = $(tr).children();
         var rowValueByColumnIndex = [];

         // For each column except the last column (the Actions column)...
         for (var colIndex = 0; colIndex < trChildren.length - 1; ++colIndex)
         {
            rowValueByColumnIndex.push($(trChildren[colIndex]).find('td').text());
         }

         _state.nextSuccessMsg = (
            (typeof clickedButtonInfo.successMsg == 'undefined')? '': clickedButtonInfo.successMsg
         );

         if (typeof clickedButtonInfo.confirmString != 'undefined')
         {
            var confirmString = clickedButtonInfo.confirmString;

            if (confirmString != '' && !confirm(confirmString))
            {
               return;
            }
         }

         if (typeof clickedButtonInfo.anchorHref != 'undefined')
         {
            window.location.href = clickedButtonInfo.anchorHref;
            return;
         }

         var ajaxParamsDataNotJsonEncoded =
         {
            action: clickedButtonInfo.phpFunctionName,
            params:
            {
               classClientParams: _state.nextRequestClassClientParams,
               resultTableParams: _state.nextRequestResultTableParams,
               rowId            : rowInfo.data.id                    ,
               valueByColIndex  : rowValueByColumnIndex
            }
         };

         if (clickedButtonInfo.jsOnClickFunctionName === undefined)
         {
            _state.ajaxParams.data = JSON.stringify(ajaxParamsDataNotJsonEncoded);
            self.setDisabledForAllInputs(true);
            $.ajax(_state.ajaxParams);
         }
         else
         {
            // Pass the default return ajax params to the button's specified onclick function.
            eval
            (
               clickedButtonInfo.jsOnClickFunctionName +
               '(_state.ajaxParams, ajaxParamsDataNotJsonEncoded);'
            );
         }
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Other private functions. ----------------------------------------------------------------//

   /*
    *
    */
   function _getButtonIndexForButton(button)
   {
      var f = 'ResultTable._getButtonIndexForButton()';
      UTILS.checkArgs(f, arguments, [HTMLInputElement]);

      var buttonIndexByCountingButtons   = $(button).index();
      var buttonIndexExcludingEditButton = (
         buttonIndexByCountingButtons + ((_state.includeEditButtonInActionsColumn)? -1: 0)
      );

      return buttonIndexExcludingEditButton;
   }

   /*
    *
    */
   function _createEditableTrFromOriginalTr(originalTr)
   {
      var f = 'ResultTable._createEditableTrFromOriginalTr()';
      UTILS.checkArgs(f, arguments, [HTMLTableRowElement]);

      var colInfoByColIndex  = _state.returnedAjaxParams.colInfoByColIndex;
      var editableTr         = TR();
      var originalTrChildren = $(originalTr).children();

      for (var colIndex = 0; colIndex < originalTrChildren.length; ++colIndex)
      {
         var originalTd = originalTrChildren[colIndex];
         var newTd      = TD({'class': $(originalTd).attr('class')});
         var valueStr   = $(originalTd).text();

         if (colIndex < originalTrChildren.length - 1)
         {
            switch (colInfoByColIndex[colIndex].isEditable)
            {
             case true:
               $(newTd).addClass('editableTd');
               $(newTd).append(INPUT({type: 'text', value: valueStr}));
               break;
             case false:
               // NOTE
               // ----
               // $.html() is used below to get and set the SPAN contents so that for example if
               // the valueStr contains HTML code for an anchor, the anchor will display rather
               // than the HTML code as a textnode.
               var span = SPAN();
               $(span).html(valueStr);
               $(newTd).append(span);
               break;
            }
         }
         else
         {
            var saveRowButton = INPUT
            (
               {type: 'button', 'class': 'saveButton', title: 'save', value: 'Save'}
            );
            var revertRowButton = INPUT
            (
               {type: 'button', 'class': 'revertButton', title: 'cancel', value: 'Revert'}
            );
            $(revertRowButton).click(_onClickRevertRowButton);
            $(saveRowButton  ).click(_onClickSaveRowButton  );
            $(newTd          ).append(revertRowButton, saveRowButton);
         }

         $(editableTr).append(newTd);
      }

      return editableTr;
   }

   /*
    *
    */
   function _assertGetDataReplyFromServerIsValid(reply)
   {
      var f = 'ResultTable._assertGetDataReplyFromServerIsValid()';
      UTILS.checkArgs(f, arguments, [Object]);

      UTILS.validator.checkObject
      (
         reply,
         {
            colInfoByColIndex: 'array'         , // Array of objects.  Expected keys checked below.
            firstRowRank     : 'int'           ,
            footer           : 'string'        ,
            heading          : 'string'        ,
            maxRowsPerPage   : 'nonNegativeInt',
            n_rowsTotal      : 'nonNegativeInt',
            offset           : 'nonNegativeInt',
            rowInfoByRowIndex: 'array'         , // Array of objects.  Expected keys checked below.
            subheading       : 'string'
         }
      );

      var colInfoByColIndex = reply.colInfoByColIndex;
      var rowInfoByRowIndex = reply.rowInfoByRowIndex;
      var n_cols            = colInfoByColIndex.length;
      var n_rows            = rowInfoByRowIndex.length;

      for (var colIndex = 0; colIndex < n_cols; ++colIndex)
      {
         UTILS.validator.checkObject
         (
            colInfoByColIndex[colIndex],
            {
               cssClassesStr: 'string',
               heading      : 'string',
               isEditable   : 'bool'
            }
         );
      }

      for (var rowIndex = 0; rowIndex < n_rows; ++rowIndex)
      {
         var rowInfo = rowInfoByRowIndex[rowIndex];

         UTILS.validator.checkObject
         (
            rowInfo,
            {
               buttonsInfo: 'array',
               data       : 'object'
            }
         );

         var rowButtonsInfo = rowInfo.buttonsInfo;
         var rowData        = rowInfo.data       ;

         UTILS.validator.checkObject
         (
            rowData,
            {
               id             : 'string',
               valueByColIndex: 'array'
            }
         );

         for (var buttonIndex = 0; buttonIndex < rowButtonsInfo.length; ++buttonIndex)
         {
            UTILS.validator.checkObject
            (
               rowButtonsInfo[buttonIndex],
               {
                  cssClassesStr: 'string',
                  titleStr     : 'string',
                  valueStr     : 'string'
               },
               {
                  anchorHref           : 'string',
                  confirmString        : 'string',
                  jsOnClickFunctionName: 'string',
                  phpFunctionName      : 'string',
                  successMsg           : 'string'
               }
            );
         }

         var valueByColIndex = rowData.valueByColIndex;

         if (valueByColIndex.length != n_cols)
         {
            throw new Exception(f, 'Unexpected number of values found in row ' + rowIndex + '.','');
         }

         for (var colIndex = 0; colIndex < n_cols; ++colIndex)
         {
            // Values are allowed to be null because results of SQL queries often contain nulls
            // and it would be burdensome to have to convert nulls to empty strings inside the
            // SQL query.  Instead, null values are treated as empty strings everywhere inside the
            // ResultTable object.
            UTILS.validator.checkType(valueByColIndex[colIndex], 'nullOrString');
         }
      }

      if (reply.firstRowRank != -1 && reply.editInfo !== null)
      {
         throw new Exception(f, 'Rows may not be both ranked and editable.', '');
      }
   }

   /*
    *
    */
   function _fillTableWithNewData(params)
   {
      var f = 'ResultTable._fillTableWithNewData()';
      UTILS.checkArgs(f, arguments, [Object]);

      var colInfoByColIndex = params.colInfoByColIndex;
      var rowInfoByRowIndex = params.rowInfoByRowIndex;

      // Inspect colInfoByColIndex to discover whether to include the actions column and an edit
      // button inside each td in the actions column.  Set both variables to default values first.
      _state.includeActionsColumn             = false;
      _state.includeEditButtonInActionsColumn = false;
      _state.n_colsEditable                   = 0;
      for (var colIndex = 0; colIndex < colInfoByColIndex.length; ++colIndex)
      {
         if (colInfoByColIndex[colIndex].isEditable)
         {
            _state.includeActionsColumn             = true;
            _state.includeEditButtonInActionsColumn = true;
            ++_state.n_colsEditable;
            break;
         }
      }

      // If no actions column is required according to the colInfoByColIndex array,
      // inspect rowInfoByRowIndex to discover whether to include the actions column.
      if (!_state.includeActionsColumn)
      {
         for (var rowIndex = 0; rowIndex < rowInfoByRowIndex.length; ++rowIndex)
         {
            if (rowInfoByRowIndex[rowIndex].buttonsInfo.length > 0)
            {
               _state.includeActionsColumn = true;
               break;
            }
         }
      }

      _state.returnedAjaxParams =  params;
      _state.includeRankColumn  = (params.firstRowRank != -1);
      _state.n_colsDataOnly     =  colInfoByColIndex.length;
      _state.n_cols             =  _state.n_colsDataOnly +
      (
         ((_state.includeRankColumn   )? 1: 0) +
         ((_state.includeActionsColumn)? 1: 0)
      );

      var thead = _domElements.thead;
      var tbody = _domElements.tbody;
      var tfoot = _domElements.tfoot;

      // Clear inputs that appear in the table body.
      _inputs.editButtons = [];

      // Clear table elements.
      $(thead).html('');
      $(tbody).html('');
      $(tfoot).html('');

      // Create Heading, Column heading, Data, and Footer TR elements.
      var trElemsH = _createTrElemsH();
      var trElemsC = _createTrElemsC();
      var trElemsD = _createTrElemsD();
      var trElemsF = _createTrElemsF();

      for (var i = 0; i < trElemsH.length; ++i) {$(thead).append(trElemsH[i]);}
      for (var i = 0; i < trElemsC.length; ++i) {$(thead).append(trElemsC[i]);}
      for (var i = 0; i < trElemsD.length; ++i) {$(tbody).append(trElemsD[i]);}
      for (var i = 0; i < trElemsF.length; ++i) {$(tfoot).append(trElemsF[i]);}

      if (_state.n_colsDataOnly > 0)
      {
         _fillCreateNewRowTable();
      }
   }

   /*
    *
    */
   function _createTrElemsH()
   {
      var f = 'ResultTable._createTrElemsH()';
      UTILS.checkArgs(f, arguments, []);

      var o       = UTILS.table;
      var p       = _state.returnedAjaxParams;
      var trElems = [];

      if (p.heading != '')
      {
         trElems.push
         (
            TR({'class': 'heading'}, o.buildTCellWithBRs('h', p.heading, {colspan: _state.n_cols}))
         );
      }

      if (p.subheading != '')
      {
         trElems.push
         (
            TR
            (
               {'class': 'subheading1'},
               o.buildTCellWithBRs('h', p.subheading, {colspan: _state.n_cols})
            )
         );
      }

      return trElems;
   }

   /*
    * Create and return the column headings row.
    */
   function _createTrElemsC()
   {
      var f = 'ResultTable._createTrElemsC()';
      UTILS.checkArgs(f, arguments, []);

      var tr = TR();
      var p  = _state.returnedAjaxParams;

      if (_state.includeRankColumn)
      {
         $(tr).append(TH({'class': 'l'}, 'Rank'));
      }

      var colInfoByColIndex = p.colInfoByColIndex;
      var shadeBool         = false;

      for (var colIndex = 0; colIndex < _state.n_colsDataOnly; ++colIndex)
      {
         $(tr).append
         (
            UTILS.table.buildTCellWithBRs
            (
               'h', colInfoByColIndex[colIndex].heading, {'class': (shadeBool)? 'l': 'd'}
            )
         );

         shadeBool = !shadeBool;
      }

      if (_state.includeActionsColumn)
      {
         $(tr).append(TH({'class': (shadeBool)? 'l': 'd'}, 'Actions'));
      }

      var trElems = [tr];

      if (!_state.includeRankColumn)
      {
         trElems.push(_createSortButtonsRow())
      }

      return trElems;
   }

   /*
    * Create all the TR elements for the data section of the table.
    */
   function _createTrElemsD()
   {
      var f = 'ResultTable._createTrElemsD()';
      UTILS.checkArgs(f, arguments, []);

      var p                 = _state.returnedAjaxParams;
      var rowInfoByRowIndex = p.rowInfoByRowIndex;
      var lastColumnIndex   = _state.n_colsDataOnly - 1;
      var prevLastColValue  = NaN;
      var rank              = p.firstRowRank;
      var trElems           = [];

      // For each filled row to be created...
      for (var rowIndex = 0; rowIndex < p.maxRowsPerPage; ++rowIndex)
      {
         var rowShadeChar = (rowIndex % 2 == 1)? 'd': 'l';

         if (rowIndex < rowInfoByRowIndex.length)
         {
            var valueByColIndex = rowInfoByRowIndex[rowIndex].data.valueByColumnIndex;
            var rankStrOrNull   = null;

            if (_state.includeRankColumn)
            {
               switch (valueByColIndex[lastColumnIndex] == prevLastColValue)
               {
                case true:
                  rankStrOrNull = '=';
                  break;
                case false:
                  rankStrOrNull    = String(rank++);
                  prevLastColValue = valueByColIndex[lastColumnIndex];
               }
            }

            var tr = _createTrElemD(rowIndex, rowShadeChar, rankStrOrNull);
         }
         else
         {
            var tr = TR({'class': 'emptyRow'});

            for (var colIndex = 0; colIndex < _state.n_cols; ++colIndex)
            {
               var cellShadeStr = rowShadeChar + ((colIndex % 2 == 1)? 'l': 'd');
               $(tr).append(TD({'class': cellShadeStr}, '\xA0')); // '\xA0 is non-breaking space.
                                                                  // '&nbsp;' shows as string in IE.
            }
         }

         trElems.push(tr);
      }

      return trElems;
   }

   /*
    * Create a single TR element for the data section of the table.
    */
   function _createTrElemD(rowIndex, rowShadeChar, rankStrOrNull)
   {
      var f = 'ResultTable._createTrElemD()';
      UTILS.checkArgs(f, arguments, ['nonNegativeInt', String, 'nullOrString']);

      var tr                = TR();
      var p                 = _state.returnedAjaxParams;
      var colInfoByColIndex = p.colInfoByColIndex;
      var rowInfoByRowIndex = p.rowInfoByRowIndex;
      var valueByColIndex   = rowInfoByRowIndex[rowIndex].data.valueByColIndex;

      if (rankStrOrNull !== null)
      {
         $(tr).append(TD({'class': rowShadeChar + 'l' + ' alignR'}, rankStrOrNull));
      }

      // For each data column...
      for (var colIndex = 0; colIndex < _state.n_colsDataOnly; ++colIndex)
      {
         var colInfo      = colInfoByColIndex[colIndex];
         var strOrNull    = valueByColIndex[colIndex];
         var cellShadeStr = rowShadeChar + ((colIndex % 2 == 1)? 'l': 'd');
         var tdAttributes = {'class': cellShadeStr + ' ' + colInfo.cssClassesStr};

         // If the first character of the strOrNull string is '<', treat as HTML.
         if (strOrNull !== null && strOrNull.substr(0, 1) == '<')
         {
            var td = TD(tdAttributes);

            // Note Regarding Javascript Injection Attacks
            // -------------------------------------------
            // The $(td).text(str) function should always be used to insert text into the HTML
            // instead of the $(td).html(str) function.  Using the latter method will cause
            // javascript included in the string to be run when the HTML is updated.
            // The $(td).html(str) function is used below, but for uneditable fields only.  The
            // purpose of this is to allow anchor tags to be used in data fields.  Allowing html in
            // uneditable fields is still a security risk because a developer may later choose to
            // set a column that was previously editable to uneditable.  That security risk is
            // considered to be outweighed by the convenience of having anchors in data fields.
            switch (colInfo.isEditable)
            {
             case true : $(td).text(strOrNull); break;
             case false: $(td).html(strOrNull); break;
            }
         }
         else
         {
            var td = UTILS.table.buildTCellWithBRs('d', strOrNull, tdAttributes);
         }

         $(tr).append(td);
      }

      if (_state.includeActionsColumn)
      {
         var cellShadeStr = rowShadeChar + ((colIndex % 2 == 1)? 'l': 'd');
         var actionsTd    = TD({'class': cellShadeStr + ' actionsTd'});
         var buttonsInfo  = rowInfoByRowIndex[rowIndex].buttonsInfo;

         if (_state.includeEditButtonInActionsColumn)
         {
            // Note Regarding Display of Buttons
            // ---------------------------------
            // The 'value' attribute of the button below is intentionally left blank so that a
            // background image for the buttons can be added.  In Firefox, styling the text as
            // transparent would would allow the background image method to work, but not in IE.
            var b = INPUT({type: 'button', 'class': 'editButton', title: 'edit', value: ''});
            $(b).click(_onClickEditRowButton);
            $(actionsTd).append(b);
         }

         for (var i = 0; i < buttonsInfo.length; ++i)
         {
            var buttonInfo = buttonsInfo[i];
            var b          = INPUT
            (
               {
                  type   : 'button'                ,
                  'class': buttonInfo.cssClassesStr,
                  title  : buttonInfo.titleStr     ,
                  value  : buttonInfo.valueStr
               }
            );

            $(b).click(_onClickCustomButtonDefault);
            $(actionsTd).append(b);
         }
         
         $(tr).append(actionsTd);
      }

      return tr;
   }

   /*
    *
    */
   function _createTrElemsF()
   {
      var f = 'ResultTable._createTrElemsF()';
      UTILS.checkArgs(f, arguments, []);

      var p                  = _state.returnedAjaxParams;
      var rowsSummaryMessage =
      (
         (p.rowInfoByRowIndex.length == 0)? 'No rows to display':
         'Rows ' + (p.offset + 1) + ' to ' +
         Math.min(p.offset + p.rowInfoByRowIndex.length, p.n_rowsTotal) + ' of ' + p.n_rowsTotal
      );

      var o       = UTILS.table;
      var trElems =
      [
         TR
         (
            {'class': 'footerRowsSummary'}, TD
            (
               {colspan: _state.n_cols, style: 'text-align: center; white-space: normal;'},
               SPAN({style: 'float: left' }, _inputs.prevPageButton),
               SPAN(                         rowsSummaryMessage    ),
               SPAN({style: 'float: right'}, _inputs.nextPageButton)
            )
         ),
      ];

      if (p.footer != '')
      {
         trElems.push
         (
            TR({'class': 'footer'}, o.buildTCellWithBRs('h', p.footer, {colspan: _state.n_cols}))
         );
      }

      $(_inputs.prevPageButton).click(_onClickPrevOrNextButton);
      $(_inputs.nextPageButton).bind('click', _onClickPrevOrNextButton, false);

      _setDisabledForPrevAndNextPageButtons(false, false);

      return trElems;
   }

   /*
    * Create a separate table to be used to create a new row.
    */
   function _fillCreateNewRowTable()
   {
      var f = 'ResultTable._fillCreateNewRowTable()';
      UTILS.checkArgs(f, arguments, []);

      var colInfoByColIndex = _state.returnedAjaxParams.colInfoByColIndex;
      var tbody             = TBODY();

      if (configParams.createNewRowHeading != '')
      {
         $(tbody).append
         (
            TR(TH({colspan: _state.n_colsEditable + 1}, configParams.createNewRowHeading))
         );
      }

      if (configParams.createNewRowDisplayColumnHeadings)
      {
         // Create column headings row.
         var trHeadings = TR();
         for (var colIndex = 0; colIndex < _state.n_colsDataOnly; ++colIndex)
         {
            var cellShadeStr = 'l' + ((colIndex % 2 == 1)? 'l': 'd');
            var colInfo      = colInfoByColIndex[colIndex];

            if (colInfoByColIndex[colIndex].isEditable)
            {
               $(trHeadings).append(TH({'class': cellShadeStr}, colInfo.heading));
            }
         }

         $(trHeadings).append(TH()      );
         $(tbody     ).append(trHeadings);
      }

      // Create row of textboxes.
      var trTextboxes = TR();
      for (var colIndex = 0; colIndex < _state.n_colsDataOnly; ++colIndex)
      {
         var cellShadeStr = 'd' + ((colIndex % 2 == 1)? 'l': 'd');

         if (colInfoByColIndex[colIndex].isEditable)
         {
            $(trTextboxes).append(TD(INPUT({'class': 'textbox', type: 'text'})));
         }
      }

      var createRowButton = INPUT
      (
         {type: 'button', 'class': 'createButton', value: configParams.createNewRowButtonText}
      );

      $(createRowButton).click(_onClickCreateRowButton);

      $(trTextboxes).append(TD(createRowButton));
      $(tbody      ).append(trTextboxes        );

      $(_domElements.createNewRowTable).html('');
      $(_domElements.createNewRowTable).append(tbody);
   }

   /*
    * @param boolPrev, boolNext
    *    The new value for the 'disabled' attribute for the respective button.
    *
    * NOTE
    * ----
    * Setting bool to true results in the respective button being disabled as expected.
    * Setting bool to false will only enable the button if there is a previous or next page.
    * This removes the need to check the page number against the max and min page numbers
    * elsewhere in code.
    */
   function _setDisabledForPrevAndNextPageButtons(boolPrev, boolNext)
   {
      var f = 'ResultTable._setDisabledForPrevAndNextPageButtons()';
      UTILS.checkArgs(f, arguments, [Boolean, Boolean]);

      var p = _state.returnedAjaxParams;

      if (p !== null) {
         var maxRowsPerPage = p.maxRowsPerPage;
         var n_rowsTotal    = p.n_rowsTotal;

         // Override enable directives if no previous or next page exists.
         if (!boolPrev) {boolPrev = (p.offset == 0 || n_rowsTotal <= maxRowsPerPage);}
         if (!boolNext) {boolNext = (p.offset >=      n_rowsTotal -  maxRowsPerPage);}
      }

      var prevPageButton = _inputs.prevPageButton;
      var nextPageButton = _inputs.nextPageButton;

      prevPageButton.disabled = boolPrev;
      nextPageButton.disabled = boolNext;

      switch (boolPrev)
      {
       case true : $(prevPageButton).addClass('disabled')   ; break;
       case false: $(prevPageButton).removeClass('disabled'); break;
      }

      switch (boolNext)
      {
       case true : $(nextPageButton).addClass('disabled')   ; break;
       case false: $(nextPageButton).removeClass('disabled'); break;
      }
   }

   /*
    * Create a row of sort buttons consisting of two buttons (asc and dsc) for each column.
    *
    * Sort buttons should only be used for tables without rank columns.
    */
   function _createSortButtonsRow()
   {
      var f = 'ResultTable._createSortButtonsRow()';
      UTILS.checkArgs(f, arguments, []);

      var p             = _state.returnedAjaxParams;
      var tr            = TR();
      var shadeBool     = false;
      var dataRowsCount = p.rowInfoByRowIndex.length;

      for (var colIndex = 0; colIndex < _state.n_colsDataOnly; ++colIndex)
      {
         var buttons =
         {
            // Note Regarding Display of Buttons
            // ---------------------------------
            // The 'value' attribute of the buttons below is intentionally left blank so that a
            // background image for the buttons can be added.  In Firefox, styling the text as
            // transparent would would allow the background image method to work, but not in IE.
            asc: INPUT({type: 'button', 'class': 'sortAscButton'}),
            dsc: INPUT({type: 'button', 'class': 'sortDscButton'})
         };

         $(buttons.asc).click(_onClickSortButton);
         $(buttons.dsc).click(_onClickSortButton);

         // Add sort buttons to class-scope variable so they can all be disabled later.
         _inputs.sortButtonPairs.push(buttons);

         if (dataRowsCount <= 1)
         {
            buttons.asc.disabled = true;
            buttons.dsc.disabled = true;
         }

         $(tr).append
         (
            TH
            (
               {'class': (shadeBool)? 'l': 'd'},
               buttons.asc, buttons.dsc
            )
         );

         shadeBool = !shadeBool;
      }

      if (_state.includeActionsColumn)
      {
         $(tr).append(TH({'class': (shadeBool)? 'l': 'd'}));
      }

      return tr;
   }

   /*
    *
    */
   function _onReceiveSuccessfulAjaxMessage(reply)
   {
      var f = 'ResultTable._onReceiveSuccessfulAjaxMessage()';
      UTILS.checkArgs(f, arguments, [Object]);

      var successMsg = _state.nextSuccessMsg;

      if (successMsg != '')
      {
         configParams.displaySuccessMessageFunction(successMsg);
      }

      _state.nextSuccessMsg = '';

      _assertGetDataReplyFromServerIsValid(reply);
      _fillTableWithNewData(reply);
      self.setDisabledForAllInputs(false);
   }

   // Initialisation functions. ---------------------------------------------------------------//

   /*
    *
    */
   function _init()
   {
      var f = 'ResultTable._init()';
      UTILS.checkArgs(f, arguments, []);

      var configParamsDefaultValues =
      {
         createNewRowHeading              : 'Create New Row'            ,
         createNewRowButtonText           : 'Create Row'                ,
         createNewRowDisplayColumnHeadings: false                       ,
         displayFailureMessageFunction    : function (msg) {alert(msg);},
         displaySuccessMessageFunction    : function (msg) {alert(msg);}
      };

      _checkObjectConfigParams(configParams             , 'allOptional'  );
      _checkObjectConfigParams(configParamsDefaultValues, 'allCompulsory');

      for (var key in configParamsDefaultValues)
      {
         if (typeof configParams[key] == 'undefined')
         {
            configParams[key] = configParamsDefaultValues[key];
         }
      }

      _domElements.table = TABLE
      (
         {'class': 'resultTable'}, _domElements.thead, _domElements.tbody, _domElements.tfoot
      );

      _initNextRequestResultTableParams();
   }

   /*
    *
    */
   function _checkObjectConfigParams(params, allOptionalORallCompulsory)
   {
      var f = 'ResultTable._checkObjectConfigParams()';
      UTILS.checkArgs(f, arguments, [Object, String]);

      var typeByKey =
      {
         createNewRowHeading              : 'string'  ,
         createNewRowButtonText           : 'string'  ,
         createNewRowDisplayColumnHeadings: 'bool'    ,
         displayFailureMessageFunction    : 'function',
         displaySuccessMessageFunction    : 'function'
      };

      switch (allOptionalORallCompulsory)
      {
       case 'allOptional':
         var compulsoryKeyTypePairs = {};
         var optionalKeyTypePairs   = typeByKey;
         break;
       case 'allCompulsory':
         var compulsoryKeyTypePairs = typeByKey;
         var optionalKeyTypePairs   = {};
         break;
       default:
         throw new Exception(f, 'Unexpected value for allOptionalORallCompulsory.', '');
      }

      UTILS.validator.checkObject(params, compulsoryKeyTypePairs, optionalKeyTypePairs);

      _state.ajaxParams.success = UTILS.ajax.createReceiveAjaxMessageFunction(
         'ResultTable', function (message, boolRemoveAfterDelay)
         {
            var f = 'ResultTable.displayFailureMessage()';
            UTILS.checkArgs(f, arguments, [String, Boolean]);

            if (boolRemoveAfterDelay) {self.setDisabledForAllInputs(false);}
            configParams.displayFailureMessageFunction(message, boolRemoveAfterDelay);
         },
         {
            updateRow: _onReceiveSuccessfulAjaxMessage,
            insertRow: _onReceiveSuccessfulAjaxMessage,
            getData  : _onReceiveSuccessfulAjaxMessage
         }
      );
   }

   /*
    * This function should be called whenever the format of the ResultTable changes
    * because the values of the old offset and orderByInfo may no longer be applicable.
    * When the classCientParams are updated is one such situation.
    */
   _initNextRequestResultTableParams = function ()
   {
      var f = 'ResultTable._initNextRequestResultTableParams()';
      UTILS.checkArgs(f, arguments, []);

      _state.nextRequestResultTableParams = {offset: 0, orderByInfo: []};
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   // HTML elements. --------------------------------------------------------------------------//

   var _inputs =
   {
      // Note Regarding Display of Buttons
      // ---------------------------------
      // The 'value' attribute of the button below is intentionally left blank so that a
      // background image for the buttons can be added.  In Firefox, styling the text as
      // transparent would would allow the background image method to work, but not in IE.
      prevPageButton: INPUT
      (
         {type: 'button', 'class': 'prevPageButton', title: 'previous page'}
      ),
      nextPageButton: INPUT
      (
         {type: 'button', 'class': 'nextPageButton', title: 'next page'}
      ),
      sortButtonPairs: []
   };

   var _domElements =
   {
      thead            : THEAD(),
      tbody            : TBODY(),
      tfoot            : TFOOT(),
      table            : null   ,
      createNewRowTable: TABLE({'class': 'resultTable_createNewRowTable'})
   };

   // Other variables. ------------------------------------------------------------------------//

   var _state =
   {
      ajaxParams:
      {
         dataType: 'json' ,
         type    : 'POST' ,
         url     : ajaxUrl,
         success : null
      },
      idOfRowSentToServerToSave       : null                        ,
      includeActionsColumn            : false                       ,
      includeEditButtonInActionsColumn: false                       ,
      includeRankColumn               : false                       ,
      n_cols                          : null                        ,
      n_colsDataOnly                  : null                        ,
      n_colsEditable                  : null                        ,
      nextRequestClassClientParams    : initialClassClientAjaxParams,
      nextRequestResultTableParams    : null                        ,
      nextSuccessMsg                  : ''                          ,
      returnedAjaxParams              : null
   };

   var self = this;

   // Initialisation code. //////////////////////////////////////////////////////////////////////

   _init();
}

/*******************************************END*OF*FILE********************************************/
