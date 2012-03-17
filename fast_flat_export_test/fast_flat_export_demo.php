<?php
require_once '../../lib/tom/php/database/mysql_pdo/PdoExtended.php';

try
{
   $pdoEx      = new PdoExtended('mysql:host=localhost;dbname=toy', 'root', '');
   $nPersons   = 10;
   $nCountries = 10;

   fillLinkPersonCountryRows($pdoEx, $nPersons, $nCountries);
   testFlatExportMethods($pdoEx, $nPersons, $nCountries);
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*
 *
 */
function fillLinkPersonCountryRows(PdoExtended $pdoEx, $nPersons, $nCountries)
{
   echo "Creating Test Data\n";
   echo ' * Getting idPersons...';
   $idPersons       = $pdoEx->queryColumn("SELECT id FROM person LIMIT $nPersons");
   $idPersonsChunks = array_chunk($idPersons, 10000);
   echo "done.\n   Got ", count($idPersons), " idPersons.\n";

   echo ' * Truncating table link_person_country...';
   $pdoEx->exec('TRUNCATE TABLE link_person_country');
   echo "done.\n";

   echo ' * Filling table link_person_country...';
   $t0 = microtime(true);
   foreach ($idPersonsChunks as $idPersons)
   {
      $values = array();

      foreach ($idPersons as $idPerson)
      {
         for ($idCountry = 1; $idCountry < $nCountries; ++$idCountry)
         {
            if (rand(0, 1))
            {
               $values[] = "($idPerson,$idCountry)";
            }
         }
      }

      $nRowsInserted = $pdoEx->exec
      (
         'INSERT INTO link_person_country (idPerson, idCountry) VALUES ' . implode(',', $values)
      );

      echo '.';
   }
   echo "done.\n   Inserted $nRowsInserted rows (", round((microtime(true) - $t0), 3), "s)\n\n";
}

/*
 *
 */
function testFlatExportMethods(PdoExtended $pdoEx, $nPersons, $nCountries)
{
   $file         = fopen('flat_export.csv', 'w');
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

   echo "maxCountries: $maxCountries\n\n";

   echo "Flat Export - Traditional\n";
   $t0 = microtime(true);
   writeFlatCsvExportTraditional($pdoEx, $file, $nPersons, $maxCountries);
   echo ' * Time taken: ', round((microtime(true) - $t0), 3), "s\n\n";

   echo "Flat Export - Fast\n";
   $t0 = microtime(true);
   writeFlatCsvExportFast($pdoEx, $file, $nPersons, $nCountries, $maxCountries);
   echo ' * Time taken: ', round((microtime(true) - $t0), 3), "s\n\n";
}

/*
 *
 */
function writeFlatCsvExportTraditional(PdoExtended $pdoEx, $file, $nPersons, $maxCountries)
{
   echo ' * Getting person names...';
   $personNameById = $pdoEx->queryIndexedColumn
   (
      'SELECT id, name
       FROM person
       LIMIT ' . $nPersons
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
function writeFlatCsvExportFast(PdoExtended $pdoEx, $file, $nPersons, $nCountries, $maxCountries)
{
   $selectFields = array('person.name');
   $joinClauses  = array();

   echo ' * Building SQL query...';
   for ($i = 0; $i < $nCountries; ++$i)
   {
      $selectFields[] = "IF(c_$i.name IS NULL, '', c_$i.name)";
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
          )
          LEFT JOIN country AS c_$i ON (c_$i.id=lpc_$i.idCountry)"
      );
   }
   echo "done.\n";

   echo ' * Getting rows...';
   $rows = $pdoEx->queryRows
   (
      'SELECT ' . implode(',', $selectFields) . '
       FROM person
       ' . implode("\n", $joinClauses) . '
       WHERE person.id<=?',
      array($nPersons)
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
