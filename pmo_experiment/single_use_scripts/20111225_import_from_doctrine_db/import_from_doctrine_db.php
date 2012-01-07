<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

require_once dirname(__FILE__) . '/../../lib/tom/php/classes/CliHelper.php';

error_reporting(-1);

$cliHelper = new CliHelper
(
   __FILE__, 'Import plans from the pmo_doctrine database into the pmo database.'
);

$config = $cliHelper->validateArgvAndReturnConfigOrOutputUsageAndDie();

$pdoEx = new PdoExtended
(
   'mysql:host=localhost;dbname=pmo_doctrine',
   'pmo-admin',
   'payme0vermuch'
);

$rows = $pdoEx->queryRows('SELECT * FROM user WHERE first_name IN (:tim,:tom)', array(':tim' => 'Tim', ':tom' => 'Tom'));

foreach ($rows as $row)
{
   echo "Row:\n";
   var_dump($row);
}

die();

$pdo->beginTransaction();
$cliHelper->performPostBeginTransactionDuties();

try
{
   importPlans($pdo);
   $cliHelper->performPreCommitDuties();

   $pdo->commit();

   $cliHelper->performPostCommitDuties();
}
catch (Exception $e)
{
   $pdo->rollback();
   $cliHelper->performPostRollbackDuties($e);
}

/*
 * 
 */
function importPlans($pdo)
{
   echo 'Getting plan_ids from pmo_doctrine...';
   echo 'done.  Got ', count($planIds), " planIds.\n";
}

/*
 *
 */
class PdoExtended extends PDO
{
   /*
    *
    */
   public function __construct($dsn, $username, $password, $driverOptions = null)
   {
      if ($driverOptions === null)
      {
         $driverOptions = array
         (
            PDO::ATTR_AUTOCOMMIT         => false,
            PDO::ATTR_PERSISTENT         => true ,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
         );
      }

      $this->pdo = new PDO($dsn, $username, $password, $driverOptions);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }

   /*
    * Usage:
    *
    * $pdo          = new PdoExtended($dsn, $username, $password);
    * $pdoStatement = $pdo->queryRows('SELECT id, name_first FROM user');
    * foreach ($pdoStatement as $row)
    * {
    *    echo $row['id'        ];
    *    echo $row['name_first'];
    * }
    */
   public function queryRows($sql, $params = array())
   {
      $pdoStatement = $this->pdo->prepare($sql);

      if ($pdoStatement === false)
      {
         $this->throwExceptionWithErrorInfo('PDO->prepare() returned false.');
      }

      $pdoStatement->execute($params);

      return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
   }

   /*
    *
    */
   public function queryColumn($sql, $boolConvertToInt = false)
   {
   }

   /*
    *
    */
   public function throwExceptionWithErrorInfo($errorDescription)
   {
      $errorInfo = $this->pdo->errorInfo();

      throw new Exception
      (
         "$errorDescription\n"                         .
         "\nSQLSTATE: {$errorInfo[0]}"                 .
         "Driver-specific error code: {$errorInfo[1]}" .
         "Driver-specific error message: {$errorInfo[2]}"
      );
   }
}
?>
