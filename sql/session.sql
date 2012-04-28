CREATE TABLE session (
	session_id 	char(32) PRIMARY KEY NOT NULL,
	expires 	int,
	data		text
);

CREATE INDEX session_exp_index ON session (expires);

GRANT ALL ON session TO "www-data";
GRANT ALL ON session TO seth;
GRANT ALL ON session TO prefedit;
