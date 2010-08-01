/**************************************************************************************************\
*
* Filename: "test_selection_sort_node_list.js"
*
* Project: Tests.
*
* Purpose: Tests for the UTILS.selecionSortNodeList() function.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testSelectionSortNodeList()
{
   var f = 'testSelectionSortNodeList()';
   UTILS.checkArgs(f, arguments, []);

   var body = document.getElementsByTagName('body')[0];

   var tbody = TBODY();

   var row;
   for (var i = 0; i < 15; ++i)
   {
      row = TR(TH({style: 'border-width 1px; border-style: solid;'}, String(i)));

      tbody.appendChild(row);
   }

   body.appendChild(TABLE(tbody));

   var childNodes = tbody.childNodes;

   UTILS.selectionSortNodeList
   (
      childNodes, 'dsc', 0, childNodes.length,

      function (a, b)
      {
         a = Number(a.firstChild.firstChild.nodeValue);
         b = Number(b.firstChild.firstChild.nodeValue);

              if (a > b) return  1;
         else if (a < b) return -1;
         else            return  0;
      }
   );
}

/*******************************************END*OF*FILE********************************************/

