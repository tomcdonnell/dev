<?php
require_once '../../lib/tom/php/database/mysql_pdo/PdoExtended.php';
require_once 'ToyDatabaseInitializer.php';

try
{
   $pdoEx            = new PdoExtended('mysql:host=localhost;dbname=test', 'root', '');
   $toyDbInitializer = new ToyDatabaseInitializer($pdoEx);
   $nPersons         = 1000;
   $nCountries       = 10;

   $toyDbInitializer->init($nPersons, $nCountries);
   testFlatExportMethods($pdoEx, $nCountries);
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*
 *
 */
function testFlatExportMethods(PdoExtended $pdoEx, $nCountries)
{
   $file1 = fopen('flat_export_1.csv', 'w');
   $file2 = fopen('flat_export_2.csv', 'w');

   echo "Flat Export - Traditional\n";
   $t0 = microtime(true);
   writeFlatCsvExportTraditional($pdoEx, $file1, $nCountries);
   echo ' * Time taken: ', round((microtime(true) - $t0), 3), "s\n\n";

   echo "Flat Export - Fast\n";
   $t0 = microtime(true);
   writeFlatCsvExportFast($pdoEx, $file2, $nCountries);
   echo ' * Time taken: ', round((microtime(true) - $t0), 3), "s\n\n";
}

/*
 *
 */
function writeFlatCsvExportTraditional(PdoExtended $pdoEx, $file, $nCountries)
{
   echo ' * Getting person names...';
   $personNameById = $pdoEx->queryIndexedColumn
   (
      'SELECT id, name
       FROM person'
   );
   echo "done.\n   Got ", count($personNameById), " names.\n";

   echo ' * For each name, getting countries and writing lines...';
   foreach ($personNameById as $idPerson => $personName)
   {
      $countryNames = $pdoEx->queryColumn
      (
         'SELECT country.name
          FROM country
          JOIN link_person_country ON (link_person_country.idCountry=country.id)
          WHERE link_person_country.idPerson=?
          ORDER BY link_person_country.id ASC',
         array($idPerson)
      );

      $nCountriesToFill = $nCountries - count($countryNames);

      if ($nCountriesToFill > 0)
      {
         $countryNames = array_merge($countryNames, array_fill(0, $nCountriesToFill, ''));
      }

      fwrite($file, "\"$personName\",\"" . implode('","', $countryNames) . "\"\n");
   }
   echo "done.\n   Wrote ", count($personNameById), " lines.\n";
}

/*
 *
 */
function writeFlatCsvExportFast(PdoExtended $pdoEx, $file, $nCountries)
{
   $selectFields = array('person.name');
   $joinClauses  = array();

   echo ' * Building SQL query...';
   for ($i = 0; $i < $nCountries; ++$i)
   {
      $selectFields[] = "IF(lpc_$i.idCountry IS NULL, 'N', 'Y')";
      $joinClauses[]  =
      (
         "LEFT JOIN link_person_country AS lpc_$i ON
          (
             lpc_$i.idPerson=person.id AND
             lpc_$i.idCountry=
             (
                SELECT id
                FROM country
                ORDER BY id ASC
                LIMIT 1
                OFFSET $i
             )
          )"
      );
   }
   echo "done.\n";

   echo ' * Getting rows...';
   $rows = $pdoEx->queryRows
   (
      'SELECT ' . implode(',', $selectFields) . '
       FROM person
       ' . implode("\n", $joinClauses)
   );
   echo "done.\n";

   echo ' * Writing CSV lines...';
   foreach ($rows as $row)
   {
      fwrite($file, '"' . implode('","', $row) . "\"\n");
   }
   echo "done.\n   Wrote ", count($rows), " lines.\n";
}
?>
