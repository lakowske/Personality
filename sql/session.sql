CREATE TABLE session (
	session_id 	char(32) PRIMARY KEY NOT NULL,
	expires 	int,
	create_time	timestamp with time zone,
	expire_time	timestamp with time zone,
	data		text
);

CREATE INDEX session_exp_index ON session (expires);
CREATE INDEX session_expire_time_index ON session (expire_time);

GRANT ALL ON session TO "www-data";
GRANT ALL ON session TO seth;
GRANT ALL ON session TO prefedit;
