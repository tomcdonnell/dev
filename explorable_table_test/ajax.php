<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et nowrap co=100 go-=b
*
* Filename: "ajax.php"
*
* Project: General.
*
* Purpose: A GUI table allowing rows to be displayed in a heirarchy of categories.  Rows in each
*          category may be expanded and contracted.  At a global level (affecting rows in all
*          categories, columns may be resized, and rows sorted by any column, in any order.
*
* Author: Tom McDonnell 2010-07-26.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_STRICT);

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/AdminActivityCategoriesRetriever.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   switch ($_POST['action'])
   {
    case 'getColumnHeadings':
      $returnArray = array
      ( 
         'idString'            ,
         'status'              ,
         'Output - Cost Centre',
         'Act'                 ,
         'Activity Title'      ,
         'Due Date'
      );
      break;
    case 'getChildCategoriesInfo':
      $retriever   = new AdminActivityCategoriesRetriever();
      $returnArray = $retriever->getChildCategoryInfoById($_POST['idCategory']);
      break;
    case 'getRowsForCategory':
      $returnArray = getRowsForCategory();
      break;
    case 'getDataRowsForCategory':
      $returnArray = getDataRows();
      break;
    default:
      throw new Exception("Unknown action '{$_POST['action']}'.");
   }

   echo json_encode(array('action' => $_POST['action'], 'reply' => $returnArray));
}
catch (Exception $e)
{
   echo $e;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 * Assume categories will come from MySQL table with columns `idString`, and `idParentString`.
 */
function getCategoryHierarchyAsArray()
{
   return array
   (
      array
      (
         'idString'       => '1'       ,
         'idParentString' => null      ,
         'name'           => 'Approved',
         'children'       => array
         (
            array
            (
               'idString'       => '1-1' ,
               'idParentString' => '1'   ,
               'name'           => 'Cons',
               'children'       => array
               (
                  array('idString' => '1-1-1', 'idParentString' => '1-1', 'name' => 'ConsultingA', 'children' => array()),
                  array('idString' => '1-1-2', 'idParentString' => '1-1', 'name' => 'ConsultingB', 'children' => array()),
                  array('idString' => '1-1-3', 'idParentString' => '1-1', 'name' => 'Management ', 'children' => array()),
                  array
                  (
                     'idString'       => '1-1-4'          ,
                     'idParentString' => '1-1'            ,
                     'name'           => 'More Categories',
                     'children'       => array
                     (
                        array('idString' => '1-1-4-1', 'idParentString' => '1-1-4', 'name' => 'Cat1', 'children' => array()),
                        array('idString' => '1-1-4-2', 'idParentString' => '1-1-4', 'name' => 'Cat2', 'children' => array()),
                        array
                        (
                           'idString'       => '1-1-4-3'              ,
                           'idParentString' => '1-1-4'                ,
                           'name'           => 'Still more categories',
                           'children'       => array
                           (
                              array
                              (
                                 'idString'       => '1-1-4-3-1',
                                 'idParentString' => '1-1-4-3'  ,
                                 'name'           => 'CatA'     ,
                                 'children'       => array()
                              ),
                              array
                              (
                                 'idString'       => '1-1-4-3-2',
                                 'idParentString' => '1-1-4-3'  ,
                                 'name'           => 'CatB'     ,
                                 'children'       => array()
                              )
                           )
                        ),
                        array
                        (
                           'idString'       => '1-1-4-4'   ,
                           'idParentString' => '1-1-4'     ,
                           'name'           => 'Category 3',
                           'children'       => array()
                        )
                     )
                  )
               )
            ),
            array('idString' => '1-2', 'idParentString' => '1', 'name' => 'ConsultingA', 'children' => array()),
            array('idString' => '1-3', 'idParentString' => '1', 'name' => 'ConsultingB', 'children' => array())
         )
      ),
      array
      (
         'idString'       => '2'           ,
         'idParentString' => null          ,
         'name'           => 'Not Approved',
         'children'       => array
         (
            array
            (
               'idString'       => '2-1'           ,
               'idParentString' => '2'             ,
               'name'           => 'Consulting - 1',
               'children'       => array()
            ),
            array
            (
               'idString'       => '2-2'           ,
               'idParentString' => '2'             ,
               'name'           => 'Consulting - 2',
               'children'       => array()
            ),
            array
            (
               'idString'       => '2-3'           ,
               'idParentString' => '2'             ,
               'name'           => 'Consulting - 3',
               'children'       => array()
            )
         )
      )
   );
}

/*
 *
 */
function getRowsForCategory()
{
   $rowsByConcatenatedCategoryName = array
   (
      'Approved|Consulting' => array(),
      'Approved|Consulting - Fee for Service' => array
      (
         array
         (
            '1',
            'complete',
            'BDCS',
            '234',
            'Time and Effort Tracking System',
            '2010-08-26'
         )
      )
   );

   $concatenatedCategoryName = $_POST['category'];

   if (!array_key_exists($concatenatedCategoryName, $rowsByConcatenatedCategoryName))
   {
      throw new Exception("Unknown concatenated category name '$concatenatedCategoryName'.");
   }

   return $rowsByConcatenatedCategoryName[$concatenatedCategoryName];
}

/*
 *
 */
function getDataRows()
{
   return array
   (
      array('1', '2', '3', '4', '5', '6'),
      array('1', '2', '3', '4', '5', '6'),
      array('1', '2', '3', '4', '5', '6'),
      array('1', '2', '3', '4', '5', '6')
   );
}


/*******************************************END*OF*FILE********************************************/
?>
