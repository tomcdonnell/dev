/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "NavPanelManager.js"
*
* Project: Time and Effort.
*
* Purpose: Object containing functions pertaining to the main navigation panel of the
*          administrator's view.
*
* Author: Tom McDonnell 2010-08-11.
*
\**************************************************************************************************/

// Object definition. //////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function NavPanelManager(navPanelDiv, explorableTable)
{
   var f = 'NavPanelManager()';
   UTILS.checkArgs(f, arguments, [HTMLDivElement, ExplorableTable]);

   // Private functions. ////////////////////////////////////////////////////////////////////////

   // Event listeners. ------------------------------------------------------------------------//

   /*
    *
    */
   function _onClickNavPanelLi(e)
   {
      try
      {
         var f = 'NavPanelManager._onClickNavPanelLi()';
         UTILS.checkArgs(f, arguments, [Object]);

         explorableTable.initRootCategoryRows(Number($(e.target).attr('id')));

         $(_state.selectedLi).removeClass('selected');

         _state.selectedLi = $(e.target);
         $(_state.selectedLi).addClass('selected');
      }
      catch (e)
      {
         UTILS.printExceptionToConsole(f, e);
      }
   }

   // Getters. --------------------------------------------------------------------------------//

   // Setters. --------------------------------------------------------------------------------//

   // Initialisation functions. ---------------------------------------------------------------//

   /*
    *
    */
   function _init()
   {
      var f = 'NavPanelManager._init()';
      UTILS.checkArgs(f, arguments, []);

      var lis   = $('[class=navPanelItem]');
      var n_lis = lis.length;

      for (var i = 0; i < n_lis; ++i)
      {
         $(lis[i]).click(_onClickNavPanelLi);
      }

      if (n_lis > 0)
      {
         _state.selectedLi = lis[0];
         $(_state.selectedLi).addClass('selected');
      }

      explorableTable.initRootCategoryRows(Number($(lis[0]).attr('id')));
   }

   // Other private functions. ----------------------------------------------------------------//

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var self = this;

   var _state =
   {
      selectedLi: null
   }

   // Private constants. ////////////////////////////////////////////////////////////////////////

   // Initialisation code. //////////////////////////////////////////////////////////////////////

   _init();
}



/*******************************************END*OF*FILE********************************************/
