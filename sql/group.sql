CREATE SEQUENCE pgroup_serial START 1;
CREATE TABLE pgroup (
	gid		int PRIMARY KEY DEFAULT NEXTVAL('pgroup_serial'),
        name            varchar(64) UNIQUE NOT NULL,
	description	varchar(64) DEFAULT NULL
);

GRANT ALL ON pgroup_serial TO "www-data";
GRANT ALL ON pgroup_serial TO prefedit;

GRANT ALL ON pgroup TO "www-data";
GRANT ALL ON pgroup TO prefedit;

