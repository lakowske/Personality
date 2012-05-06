CREATE SEQUENCE puser_serial START 1;
CREATE TABLE puser ( 
        --create the personality user table
	uid		int PRIMARY KEY DEFAULT NEXTVAL('puser_serial'),
        gid             int REFERENCES pgroup (gid),
	username	varchar(128) UNIQUE,
	password	varchar(256),
       	inception	timestamp with time zone,
	first_name	varchar(32) DEFAULT NULL,
	last_name	varchar(64) DEFAULT NULL,
        email           varchar(128) UNIQUE
);

GRANT ALL ON puser_serial TO "www-data";
GRANT ALL ON puser_serial TO prefedit;

GRANT ALL ON puser TO "www-data";
GRANT ALL ON puser TO prefedit;
