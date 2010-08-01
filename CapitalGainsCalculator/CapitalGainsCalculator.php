<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "CapitalGainsCalculator.php"
*
* Project: Tax return for financial year 2007/2008.
*
* Purpose: Calculate capital gains on share trading data exported from
*          comsec.com.au for the purpose of completing my 2007/2008 tax return.
*
* Author: Tom McDonnell 2008-09-16.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/common/php/Utils_validator.php';
require_once dirname(__FILE__) . '/common/php/Utils_date.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class CapitalGainsCalculator
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    * Constructor.  Validates and stores $rows array.
    */
   public function __construct($rows)
   {
      if (!is_array($rows))
      {
         throw new Exception('Unexpected type.  Expected array.');
      }

      // Validate each row and add timestamps.
      foreach ($rows as $rowKey => $row)
      {
         Utils_validator::checkArray($row, self::$typesByRequiredKeys);

         if (!in_array($row['buyOrSell'], array('B', 'S')))
         {
            throw new Exception("Expected 'B' or 'S'.  Received '{$row['buyOrSell']}");
         }

         // Add date timestamps to the row.
         foreach (array('trade', 'settlement') as $dateName)
         {
            $rows[$rowKey][$dateName . 'Timestamp'] =
            (
               Utils_date::getTimestampFromDateString($row['tradeDate'], 'dd/mm/yyyy')
            );
         }
      }

      // Store unsorted rows.
      $this->rows = $rows;
   }

   // Getters. --------------------------------------------------------------------------------//

   /*
    *
    */
   function getCapitalGainsInfo($dateStartTs, $dateFinishTs)
   {
      $capitalGainsInfo = array();

      foreach ($this->rows as $row)
      {
         $settlementTs = $row['settlementTimestamp'];

         // If the transaction was a 'sell' that occurred during the specified time period...
         if
         (
            $row['buyOrSell'] == 'S' &&
            $dateStartTs <= $settlementTs && $settlementTs <= $dateFinishTs
         )
         {
            $capitalGainsInfo[] = array
            (
               'settlementTimestamp'    => $settlementTs,
               'settlementDate'         => $row['settlementDate'],
               'stockCode'              => $row['stockCode'     ],
               'n_units'                => $row['n_units'       ],
               'avgSellPrice'           => $row['avg_price'     ],
               'totalSellPrice'         => $row['avg_price'     ] * $row['n_units'],
               'brokerage'              => $row['brokerage'     ],
               'buyTransactionsSummary' =>
               (
                  self::getBuyTransactionsSummary($row['stockCode'], $row['n_units'], $settlementTs)
               )
            );
         }
      }

      return $capitalGainsInfo;
   }

   // Other public functions. -----------------------------------------------------------------//

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function compareRowsUsingSettlementTimestamp($a, $b)
   {
      return $a['settlementTimestamp'] - $b['settlementTimestamp'];
   }

   /*
    *
    */
   private function getBuyTransactionsSummary($stockCode, $n_units, $settlementTimestamp)
   {
      $relevantRows = $this->getRowsRelevantToStockCode($stockCode);

      // Sort relevant rows in cronological order.
      usort($relevantRows, array('CapitalGainsCalculator', 'compareRowsUsingSettlementTimestamp'));

      // To hold 'buy' transaction information on all the stocks currently held.
      $stocksHeldRows = array();

      foreach ($relevantRows as $row)
      {
         switch ($row['buyOrSell'])
         {
          case 'B':
            $stocksHeldRows[] = $row;
            break;
          case 'S':
            $stocksBoughtInfo = self::subtractStocksSoldFromStocksHeld($row, $stocksHeldRows);
            if ($row['settlementTimestamp'] == $settlementTimestamp)
            {
               return $stocksBoughtInfo;
            }
            break;
          default:
            throw new Exception("Expected 'B' or 'S'.  Received '{$row['buyOrSell']}'.");
         }
      }

      throw new Exception
      (
         "No sell transaction found matching stockCode '$stockCode'" .
         " and settlement timestamp '$settlementTimestamp'."
      );
   }

   /*
    *
    */
   private static function subtractStocksSoldFromStocksHeld($stockSoldRow, $stocksHeldRows)
   {
      assert('$stockSoldRow["buyOrSell"] == "S"');

      // ASSUMPTION: Array $stocksHeldRows is sorted in cronological order of settlement date.

      $relevantBuysInfo = array();

      $n_unitsToSell  = $stockSoldRow['n_units'];
      $totalPricePaid = 0;
      $totalBrokerage = 0;

      // For each buy transaction...
      foreach ($stocksHeldRows as $stockHeldKey => $stockHeldRow)
      {
         $n_unitsBought = $stockHeldRow['n_units'];

         // If n_units sold is less than n_units bought in this transaction...
         if ($n_unitsToSell < $n_unitsBought)
         {
            // Info for this transaction.
            $n_unitsSold = $n_unitsToSell;
            $avgBuyPrice = $stockHeldRow['avg_price'];
            $brokerage   = $stockHeldRow['brokerage'] * ($n_unitsSold / $n_unitsBought);

            // Update $stocksHeldRows.
            $stocksHeldRows[$stockHeldKey]['n_units'  ] -= $n_unitsSold;
            $stocksHeldRows[$stockHeldKey]['brokerage'] -= $brokerage;
         }
         else
         {
            // Info for this transaction.
            $n_unitsSold = $n_unitsBought;
            $avgBuyPrice = $stockHeldRow['avg_price'];
            $brokerage   = $stockHeldRow['brokerage'];

            // Update $stocksHeldRows.
            unset($stocksHeldRows[$stockHeldKey]);
         }

         $n_unitsToSell  -= $n_unitsSold;
         $totalPricePaid += $n_unitsSold * $avgBuyPrice;
         $totalBrokerage += $brokerage;

         $relevantBuysInfo[] = array
         (
            'n_units'     => $n_unitsSold,
            'avgBuyPrice' => $avgBuyPrice,
            'brokerage'   => $brokerage
         );
      }

      $n_unitsSold = $stockSoldRow['n_units'] - $n_unitsToSell;

      return array
      (
         'buys'                  => $relevantBuysInfo,
         'totalBuyPrice'         => $totalPricePaid,
         'totalBrokerage'        => $totalBrokerage,
         'avgBuyPrice'           => ($n_unitsSold == 0)? null: $totalPricePaid / $n_unitsSold,
         'avgBrokerage'          => ($n_unitsSold == 0)? null: $totalBrokerage / $n_unitsSold,
         'n_unitsUnaccountedFor' => $n_unitsToSell
      );
   }

   /*
    *
    */
   private function getAvgBuyPriceFromStocksBoughtInfo($stocksBoughtInfo)
   {
      assert('array_key_exists("buys"                 , $stocksBoughtInfo)');
      assert('array_key_exists("n_unitsUnaccountedFor", $stocksBoughtInfo)');

      $totalUnitsBought = 0;
      $totalPricePaid   = 0;

      foreach ($stocksBoughtInfo['buys'] as $info)
      {
         $totalUnitsBought += $info['n_units'];
         $totalPricePaid   += $info['n_units'] * $info['avgBuyPrice'];
      }

      // ASSUMPTION:
      //   Unaccounted for units are assumed to have been purchased
      //   at the average price of the units that are accounted for.

      return $totalPricePaid / $totalUnitsBought;
   }

   /*
    *
    */
   private function getRowsRelevantToStockCode($stockCode)
   {
      $relevantRows = array();

      foreach ($this->rows as $row)
      {
         if ($row['stockCode'] == $stockCode)
         {
            $relevantRows[] = $row;
         }
      }

      return $relevantRows;
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   private $rows = null;

   private static $typesByRequiredKeys = array
   (
      'contractNo'     => 'ctype_digit'    ,
      'tradeDate'      => 'date_dd/mm/yyyy',
      'buyOrSell'      => 'ctype_alpha'    ,
      'stockCode'      => 'ctype_alpha'    ,
      'n_units'        => 'ctype_digit'    ,
      'avg_price'      => 'numeric'        ,
      'brokerage'      => 'numeric'        ,
      'netProceeds'    => 'numeric'        ,
      'settlementDate' => 'date_dd/mm/yyyy',
      'contractStatus' => 'ctype_alpha'
   );
}     

/*******************************************END*OF*FILE********************************************/
?>
