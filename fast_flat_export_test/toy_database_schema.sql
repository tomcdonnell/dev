CREATE TABLE person (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(32) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (name)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE country (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(64) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (name)
);

CREATE TABLE link_person_country (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  idPerson int(10) unsigned NOT NULL,
  idCountry int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  INDEX (idPerson),
  INDEX (idCountry),
  UNIQUE KEY idPerson_idCountry (idPerson, idCountry)
);

ALTER TABLE link_person_country
ADD CONSTRAINT FOREIGN KEY (idPerson) REFERENCES person (id),
ADD CONSTRAINT FOREIGN KEY (idCountry) REFERENCES country (id);
