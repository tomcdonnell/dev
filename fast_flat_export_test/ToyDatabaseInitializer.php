<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

/*
 *
 */
class ToyDatabaseInitializer
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct(PdoExtended $pdoEx)
   {
      $this->_pdoEx = $pdoEx;
   }

   /*
    *
    */
   public function init($nPersons, $nCountries)
   {
      $pdoEx = $this->_pdoEx;

      echo "Creating and Initializing Test Database\n";
      echo ' * Dropping database `toy`...';
      $pdoEx->exec('DROP DATABASE IF EXISTS toy');
      echo "done.\n";

      echo ' * Creating database `toy`...';
      $pdoEx->exec('CREATE DATABASE toy');
      $pdoEx->exec('USE toy');
      echo "done.\n";

      $this->_createTables();

      echo ' * Filling table `person`...';
      $t0            = microtime(true);
      $nRowsAffected = $pdoEx->exec
      (
         "INSERT INTO person (name)
          SELECT DISTINCT CONCAT(first_name, ' ', surname)
          FROM dg_test_data.dg_first_names AS first_names
          JOIN dg_test_data.dg_surnames
          LIMIT $nPersons"
      );
      echo 'done (', round(microtime(true) - $t0, 3), "s).\n";

      if ($nRowsAffected != $nPersons)
      {
         throw new Exception("Expected $nPersons to be created.  $nRowsAffected created.");
      }

      echo ' * Filling table `country`...';
      $t0            = microtime(true);
      $nRowsAffected = $pdoEx->exec
      (
         "INSERT INTO country (name)
          SELECT country
          FROM dg_test_data.dg_countries
          LIMIT $nCountries"
      );
      echo 'done (', round(microtime(true) - $t0, 3), "s).\n";

      if ($nRowsAffected != $nCountries)
      {
         throw new Exception("Expected $nCountries to be created.  $nRowsAffected created.");
      }

      $this->_fillLinkPersonCountryRows($nPersons, $nCountries);
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private function _createTables()
   {
      $pdoEx = $this->_pdoEx;

      echo ' * Creating table `person`...';
      $pdoEx->exec
      (
         'CREATE TABLE person (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(32) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY (name)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
      );
      echo "done.\n";

      echo ' * Creating table `country`...';
      $pdoEx->exec
      (
         'CREATE TABLE country (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(64) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY (name)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
      );
      echo "done.\n";

      echo ' * Creating table `link_person_country`...';
      $pdoEx->exec
      (
         'CREATE TABLE link_person_country (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            idPerson int(10) unsigned NOT NULL,
            idCountry int(10) unsigned NOT NULL,
            PRIMARY KEY (id),
            INDEX (idPerson),
            INDEX (idCountry),
            UNIQUE KEY idPerson_idCountry (idPerson, idCountry)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
      );
      echo "done.\n";

      echo ' * Adding foreign key constraints...';
      $pdoEx->exec
      (
         'ALTER TABLE link_person_country
          ADD CONSTRAINT FOREIGN KEY (idPerson) REFERENCES person (id),
          ADD CONSTRAINT FOREIGN KEY (idCountry) REFERENCES country (id)'
      );
      echo "done.\n";
   }

   /*
    *
    */
   private function _fillLinkPersonCountryRows($nPersons, $nCountries)
   {
      echo ' * Getting idPersons...';
      $idPersons       = $this->_pdoEx->queryColumn("SELECT id FROM person LIMIT $nPersons");
      $idPersonsChunks = array_chunk($idPersons, 5000);
      echo "done.\n   Got ", count($idPersons), " idPersons.\n";

      echo ' * Filling table link_person_country...';
      $t0 = microtime(true);
      foreach ($idPersonsChunks as $idPersons)
      {
         $values = array();

         foreach ($idPersons as $idPerson)
         {
            for ($idCountry = 1; $idCountry <= $nCountries; ++$idCountry)
            {
               if (rand(0, 1))
               {
                  $values[] = "($idPerson,$idCountry)";
               }
            }
         }

         $nRowsInserted = $this->_pdoEx->exec
         (
            'INSERT INTO link_person_country (idPerson, idCountry) VALUES ' . implode(',', $values)
         );

         echo '.';
      }
      echo "done.\n   Inserted $nRowsInserted rows (", round((microtime(true) - $t0), 3), "s).\n\n";
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   private $_pdoEx = null;
}
?>
