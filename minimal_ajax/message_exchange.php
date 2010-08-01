<?php
/**************************************************************************************************\
*
* Filename: "message_exchange.php"
*
* Project: Minimal AJAX.
*
* Purpose: This is the ONLY file to which messages from the client should be sent.
*          The messages are received here and then dispatched to relevant handlers.
*
* Author: Tom McDonnell 2007.
*
\**************************************************************************************************/

// Globally executed code. /////////////////////////////////////////////////////////////////////////

$messageExchange = new MessageExchange();

$messageExchange->receive(json_decode(file_get_contents('php://input')));

// Class definitions. //////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class MessageExchange
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    * Push the given message to the $outgoingMsgs array.
    * The $outgoingMsgs array is sent once all incoming messages have been processed.
    *
    * @param $header  {string}
    * @param $payload {      }
    */
   public function send($header, $payload)
   {
      assert('is_string($header)');
      assert('isset($payload)'   );

      $this->outgoingMsgs[] = array($header, $payload);
   }

   /*
    * Receive, interpret, and dispatch each message in the given array.
    */
   public function receive($msgs)
   {
      assert('is_array($msgs)');

      $replyMsgs = array();

      foreach ($msgs as $msg)
      {
         assert('is_array($msg)');
         assert('count($msg) == 2');

         $header  = $msg[0];
         $payload = $msg[1];

         switch ($header)
         {
          case 'test':
            $this->send
            (
               'test_reply', 'Test message received.  The payload was "' . $payload . '".'
            );
            break;

          default:
            throw new Exception('Unknown message header "' . $header . '" received.');
         }
      }

      // Send accumulated outgoing messages now that all incoming messages have been processed.
      if (count($this->outgoingMsgs) > 0)
      {
         echo json_encode($this->outgoingMsgs);
      }
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   private $outgoingMsgs = array();
}

/*******************************************END*OF*FILE********************************************/
?>
