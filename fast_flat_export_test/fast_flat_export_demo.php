<?php
require_once '../../lib/tom/php/database/mysql_pdo/PdoExtended.php';
require_once 'ToyDatabaseInitializer.php';

try
{
   $pdoEx            = new PdoExtended('mysql:host=localhost;dbname=test', 'root', '');
   $toyDbInitializer = new ToyDatabaseInitializer($pdoEx);

   $outputFile = fopen('output_varying_n_persons_60.csv', 'w');
   fwrite($outputFile, '"n_persons","n_countries","seconds_traditional","seconds_fast"' . "\n");

   $nCountries = 60;
   $trialNo    =  0;

   for ($nPersons = 1; $nPersons < 100000; $nPersons = ceil($nPersons * 1.05))
   {
      ++$trialNo;
      echo "Trial $trialNo (nPersons: $nPersons, nCountries: $nCountries)\n";
      echo "-------------------------------------------------------------\n\n";

      $toyDbInitializer->init($nPersons, $nCountries);

      $file1 = fopen('flat_export_1.csv', 'w');
      $file2 = fopen('flat_export_2.csv', 'w');

      echo "Flat Export - Traditional\n";
      $t0 = microtime(true);
      writeFlatCsvExportTraditional($pdoEx, $file1);
      $timeTaken1 = round(microtime(true) - $t0, 3);
      echo " * Time taken: {$timeTaken1}s\n\n";

      echo "Flat Export - Fast\n";
      $t0 = microtime(true);
      writeFlatCsvExportFast($pdoEx, $file2);
      $timeTaken2 = round(microtime(true) - $t0, 3);
      echo " * Time taken: {$timeTaken2}s\n\n";

      fwrite($outputFile, "\"$nPersons\",\"$nCountries\",\"$timeTaken1\",\"$timeTaken2\"\n");
   }
}
catch (Exception $e)
{
   echo $e->getMessage(), "\n";
}

/*
 *
 */
function writeFlatCsvExportTraditional(PdoExtended $pdoEx, $file)
{
   echo ' * Getting greatest number of countries visited by a single person...';
   $maxCountries = $pdoEx->queryField
   (
      'SELECT MAX(nCountries)
       FROM
       (
          SELECT idPerson, COUNT(*) AS nCountries
          FROM link_person_country
          GROUP BY idPerson
       ) AS dummy'
   );
   echo "done ($maxCountries).\n";

   echo ' * Writing headings row...';
   $headings = array('person_name');
   for ($i = 0; $i < $maxCountries; ++$i) {$headings[] = "country_$i";}
   fwrite($file, '"' . implode('","', $headings) . "\"\n");
   echo "done.\n";

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

      $nCountriesToFill = $maxCountries - count($countryNames);

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
function writeFlatCsvExportFast(PdoExtended $pdoEx, $file)
{
   echo ' * Getting countries list for headings row...';
   $countryNameById = $pdoEx->queryIndexedColumn
   (
      'SELECT id, name
       FROM country
       ORDER BY id ASC'
   );
   $countryIds   = array_keys($countryNameById);
   $countryNames = array_values($countryNameById);
   echo "done.\n";

   echo ' * Building SQL query...';
   $selectFields = array('person.name');
   $joinClauses  = array();
   $parameters   = array();
   for ($i = 0, $nCountries = count($countryNameById); $i < $nCountries; ++$i)
   {
      $selectFields[] = "IF(lpc_$i.idCountry IS NULL, 'N', 'Y')";
      $parameters[]   = $i + 1;
      $joinClauses[]  =
      (
         "LEFT JOIN link_person_country AS lpc_$i ON
          (
             lpc_$i.idPerson=person.id AND
             lpc_$i.idCountry=?
          )"
      );
   }
   echo "done.\n";

   echo ' * Getting rows...';
   $rows = $pdoEx->queryRows
   (
      'SELECT ' . implode(',', $selectFields) . '
       FROM person
       ' . implode("\n", $joinClauses),
      $parameters
   );
   echo "done.\n";

   echo ' * Writing CSV lines...';
   fwrite($file, '"person_name","' . implode('","', $countryNames) . "\"\n");
   foreach ($rows as $row)
   {
      fwrite($file, '"' . implode('","', $row) . "\"\n");
   }
   echo "done.\n   Wrote ", count($rows), " lines.\n";
}
?>
