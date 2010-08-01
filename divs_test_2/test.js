/**************************************************************************************************\
*
* @fileoverview 
*
* Filename: "test.js"
*
* Purpose: Functions that test language features are stored here.
*
* @author: Tom McDonnell 2007.
*
\**************************************************************************************************/

/*
 *
 */
function testObjectFunctionAndPrimitiveTypes()
{
   var DOMelem    = document.createElement('div');
   var TestObject = function () {this.testVar = '"String"';};

   // Create an array of elements of various types.
   var a = [DOMelem, new TestObject(), {}, function () {}, '"String"', 1, true];

   var str = '';
   for (var i = 0; i < a.length; ++i)
   {
      var t = a[i];

      str += t + '\n'
           + '  t.constructor == HTMLDivElement = ' + (t.constructor == HTMLDivElement) + '\n'
           + '  t.constructor == TestObject     = ' + (t.constructor == TestObject    ) + '\n'
           + '  t.constructor == Object         = ' + (t.constructor == Object        ) + '\n'
           + '  t.constructor == Function       = ' + (t.constructor == Function      ) + '\n'
           + '  t.constructor == String         = ' + (t.constructor == String        ) + '\n'
           + '  t.constructor == Number         = ' + (t.constructor == Number        ) + '\n'
           + '  t.constructor == Boolean        = ' + (t.constructor == Boolean       ) + '\n'
           + '  typeof t                        = ' + typeof t                          + '\n \n';
   }

   console.info(str);
}

/*
 *
 */
var TestObject
= function ()
{
   console.info('Inside global object "TestObject", this.TestObject = "' + this.TestObject + '".');

   this.init
   = function ()
   {
      console.info('Inside function "TestObject.init()", this.init = "' + this.init + '".');
   };

   this.init();
};

/*
 *
 */
function testThisProperty()
{

   console.info('Inside global function "testThisProperty", this.testThisProperty = "' + this.testThisProperty + '".');

   var t = new TestObject();
}

/*******************************************END*OF*FILE********************************************/
