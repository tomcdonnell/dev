/**************************************************************************************************\
*
* Filename: "message_exchange.js"
*
* Project: Minimal AJAX.
*
* Purpose: This is the ONLY file to which messages from the server should be sent.
*          The messages are received here and then dispatched to relevant handlers.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

var messageExchange = new
(
   function MessageExchange()
   {
      var f = 'MessageExchange()';
      UTILS.checkArgs(f, arguments, []);

      // Priviliged functions. ///////////////////////////////////////////////////////////////

      /**
       * To initiate the event driven message cycle (process replies, send
       * messages, process replies, ...), an initial message batch must be sent.  
       *
       * @param msgs {Array}
       *    Array of messages.  Each message is a two element
       *    array [header, payload].  The header must be a string.
       */
      this.sendInitialMessageBatch = function (msgs)
      {
         var f = 'MessageExchange.sendInitialMessageBatch()';
         UTILS.checkArgs(f, arguments, [Array]);

         // Check format of messages.
         var msg;
         for (var i = 0, len = msgs.length; i < len; ++i)
         {
            msg = msgs[i];

            if (msg.constructor != Array || msg.length != 2 || msg[0].constructor != String)
            {
               throw new Exception
               (
                  f, 'Incorrect message format.', 'Expected [[header, payload], ...].'
               ); 
            }
         }

         ajaxPort.send(msgs);
      };

      /*
       * Push the given message to the outgoingMsgs array.
       * The outgoingMsgs array is sent once all incoming messages have been processed.
       *
       * @param header  {String}
       * @param payload {      }
       */
      this.send = function (header, payload)
      {
         var f = 'MessageExchange.send()';
         UTILS.checkArgs(f, arguments, [String, 'Defined']);

         outgoingMsgs.push([header, payload]);
      };

      // Private functions. //////////////////////////////////////////////////////////////////

      /*
       * Receive, interpret, and dispatch each message in the given array.
       */
      function receive(msgs)
      {
         var f = 'MessageExchange.receive()';
         UTILS.checkArgs(f, arguments, [Array]);

         var msg, header, payload;
         for (var i = 0, len = msgs.length; i < len; ++i)
         {
            msg = msgs[i];

            UTILS.assert(f, 0, msg.constructor == Array);
            UTILS.assert(f, 1, msg.length      ==     2);

            header  = msg[0];
            payload = msg[1];

            switch (header)
            {
             case 'test_reply':
               console.info(f, payload);
               break;

             default:
               throw new Exception(f, 'Unknown message header. "' + header + '".', '');
            }
         }

         // Send accumulated outgoing messages now that all incoming messages have been processed.
         if (outgoingMsgs.length > 0)
         {
            ajaxPort.send(outgoingMsgs);
         }
      }

      // Private variables. //////////////////////////////////////////////////////////////////

      var ajaxPort = new AjaxPort('message_exchange.php', receive);

      var outgoingMsgs = [];
   }
)();

/*******************************************END*OF*FILE********************************************/

