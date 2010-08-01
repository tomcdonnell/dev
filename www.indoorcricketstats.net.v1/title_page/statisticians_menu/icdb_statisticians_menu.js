/**************************************************************************************************\
*
* Filename: "icdb_title_page.js"
*
* Project: IndoorCricketStats.net
*
* Purpose: Javascripts for web page "icdb_title_page.php".
*
* Author: Tom McDonnell 2007
*
\**************************************************************************************************/

// Event driven functions. -----------------------------------------------------------------------//

/*
 * PRIMARY
 */
function onClickAddMatchRecord()
{
   document.getElementById('statisticiansMenuFormId').action
     = 'insert_match/icdb_insert_match_p1.php';
}

/*
 * PRIMARY
 */
function onClickModifyMatchRecord()
{
   document.getElementById('statisticiansMenuFormId').action
     = 'modify_match/icdb_modify_match.php';
}

/*
 * PRIMARY
 */
function onClickDeleteMatchRecord()
{
   document.getElementById('statisticiansMenuFormId').action
     = 'delete_match/icdb_delete_match.php';
}

/*
 * PRIMARY
 */
function onClickModifyPlayerRecord()
{
   document.getElementById('statisticiansMenuFormId').action
     = 'modify_player/icdb_modify_player.php';
}

/*******************************************END*OF*FILE********************************************/
