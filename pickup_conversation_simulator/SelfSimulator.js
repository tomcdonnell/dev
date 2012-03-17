/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

/*
 *
 */
function SelfSimulator(div)
{
   // Privileged functions. /////////////////////////////////////////////////////////////////////

   /*
    *
    */
   this.converseWithPotentialMate(female)
   {
      var topics   = [];
      var nReplies = 0;
      var newTopic = '';
      var thought  = '';
      var question = '';
      var reply    = _self._speak(female, _self._getRandomGreeting());

      while (true)
      {
         if (reply != '')
         {
            ++nReplies;
            topics = topics.concat(_self._extractTopicsFromText(reply));
            _self._makeEncouragingGestureOrRemark();
            _self._think(nReplies, topics.length);
            continue;
         }

         if (topics.length > 0)
         {
            newTopic = conversationTopics.pop();
            question = _self._getRandomQuestionAboutTopic(newTopic);
            reply    = _self._speak(female, question);
            continue;
         }

         switch (_self._areWeHavingSexYet(female))
         {
          case 'yes': return EXIT_SUCCESS;
          case 'no' : return EXIT_FAILURE;
         }
      }
   };

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   function _speak(female, text)
   {
      $(div).append(P({'class': 'speech'}, text));
      return female.getReply(text);
   }

   /*
    *
    */
   function _makeEncouragingGestureOrRemark()
   {
      if (Math.random() > 0.2)
      {
         var class = 'gesture';
         var array = _encouragingGestures;
      }
      else
      {
         var class = 'speech';
         var array = _encouragingRemarks;
      }

      $(div).append(P({'class': class}, array[Math.floor(random() * array.length)]));
   }

   /*
    *
    */
   function _think(nReplies, nTopics)
   {
      var thought = 
      $(div).append(P({'class': 'thought'}, '(' + thought + ')'));
   }

   /*
    *
    */
   function _extractTopicsFromText(text)
   {
      // add synchronous ajax function.
      // At server highlight topics like: 'I grew up in _Canada_.  My _family_ had a _farm_ there.'
   }

   /*
    *
    */
   function _getRandomQuestionAboutTopic(nReplies)
   {
      return 
   }

   /*
    *
    */
   function _areWeHavingSexYet(female)
   {
      return 'no';
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   var _self = this;

   var _encouragingGestures =
   [
      'nods',
      'smiles'
   ];

   var _encouragingRemarks =
   [
      'Ah, now I get it.'        ,
      'Fascinating.'             ,
      'I can relate to that.'    ,
      'I see.'                   ,
      'Interesting.'             ,
      'Right.'                   ,
      'That\'s very interesting.',
      'Yes.'
   ];

   var _proddingRemarks =
   [
      'And?'                                  ,
      'Carry on.'                             ,
      'Continue.'                             ,
      'Go on.'                                ,
      'Please continue.'                      ,
      'And then?'                             ,
      'And then what happened?'               ,
      'I really am interested.  Keep talking.',
      'Yes, and?'
   ];

   var _thoughtsByConfidenceLevel =
   [
      'Not to worry, early days yet.  Plenty of topics in store.',
      'Still not having sex!  Arrgh!  What must a man do?'
   ];
}
