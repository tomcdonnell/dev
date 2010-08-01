/**************************************************************************************************\
*
* Filename: "inheritance_test.js"
*
* Purpose: JavaScripts for "inheritance_test.php".
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

window.addEventListener('load', init, false);

var modules = [];

var database = [123];

/*
 *
 */
function init()
{
   try
   {
      var f = 'init()';

      var moduleZero = new QuestionModule(0, 'What is the answer (0)?');
      modules[0] = moduleZero;

      var moduleOne  = new QuestionDollarsModule(1, 'What is the answer (1)?');
      modules[1] = moduleOne;

      redrawAllModules();

      moduleZero.setRedrawRequired(true);

      redrawAllModules();

      moduleOne.setRedrawRequired(true);

      redrawAllModules();

      moduleZero.setRedrawRequired(false);
      moduleOne.setRedrawRequired(false);

      redrawAllModules();
   }
   catch (e)
   {
      UTILS.printExceptionToConsole(f, e);
   }
}

/*
 *
 */
function redrawAllModules()
{
   console.info('Redrawing all modules.');

   for (var i = 0; i < modules.length; ++i)
   {
      modules[i].redrawIfNecessary();
   }
}

/*
 *
 */
console.info('Declaring Module.');
function Module(idArg)
{
   var f = 'Module()';
   UTILS.assert(f, 0, arguments.length == 0 || arguments.length == 1);
   UTILS.assertUndefinedOrTypeAndCondition(f, 1, idArg, Number, idArg >= 0);

   this.getId             = function ()     {return id;};
   this.setRedrawRequired = function (bool) {redrawRequired = bool;};

   this.redrawIfNecessary = function ()
   {
      if (redrawRequired)
      {
         this.redraw();
      }
      else
      {
         console.info('   Redraw not required for module ' + id + '.');
      }
   };

   this.redraw = function () {console.info('   Module (id ' + id + '): Redrawing.');};

   const id = idArg;

   var redrawRequired = false;
}

/*
 *
 */
QuestionModule.inherits(Module)
function QuestionModule(idArg, questionArg)
{
   var f = 'QuestionModule()';
   UTILS.assert(f, 0, arguments.length == 0 || arguments.length == 2);
   UTILS.assertUndefinedOrTypeAndCondition(f, 1, idArg      , Number, idArg >= 0);
   UTILS.assertUndefinedOrTypeAndCondition(f, 2, questionArg, String, true      );

   Module.apply(this, [idArg]);

   // NOTE: All public functions of Module are inherited.

   // Overriding parent function.
   this.redraw = function ()
   {
      console.info('   QuestionModule (id ' + this.getId() + '): Redrawing.');

      var answer = this.formatAnswer(database[0]);

      console.info('   Question: "' + question + '" Answer: "' + answer + '".');
   };

   this.formatAnswer = function (answer)
   {
      return answer;
   }

   const question = questionArg;
}

/*
 *
 */
QuestionDollarsModule.inherits(QuestionModule)
function QuestionDollarsModule(idArg, questionArg)
{
   var f = 'QuestionDollarsModule()';
   UTILS.assert(f, 0, arguments.length == 0 || arguments.length == 2);
   UTILS.assertUndefinedOrTypeAndCondition(f, 1, idArg      , Number, idArg >= 0);
   UTILS.assertUndefinedOrTypeAndCondition(f, 2, questionArg, String, true      );

   QuestionModule.apply(this, [idArg, questionArg]);

   // NOTE: All public functions of QuestionModule are inherited.

   // Overriding parent function.
   this.formatAnswer = function (answer)
   {
      return '$' + answer;
   }
}

/*******************************************END*OF*FILE********************************************/
