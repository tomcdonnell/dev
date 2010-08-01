/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "testDateSelectors.js"
*
* Project: Tests.
*
* Purpose: Tests for the DateSelector, PeriodSelector, and WeekSelector objects.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function testDateSelectors()
{
   var f = 'testDateSelectors()';
   UTILS.checkArgs(f, arguments, []);

   var selectorDate       = new SelectorDate();
   var selectorDatePeriod = new SelectorDatePeriod();

   var body = document.body;

   var ds = selectorDate.getSelectors();
   var ps = selectorDatePeriod.getSelectors();

   body.appendChild(ds.day  );
   body.appendChild(ds.month);
   body.appendChild(ds.year );
   body.appendChild(BR());
   body.appendChild(ps.start.day  );
   body.appendChild(ps.start.month);
   body.appendChild(ps.start.year );
   body.appendChild(ps.finish.day  );
   body.appendChild(ps.finish.month);
   body.appendChild(ps.finish.year );
}

/*******************************************END*OF*FILE********************************************/
