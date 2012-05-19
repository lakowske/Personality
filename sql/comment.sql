CREATE SEQUENCE comment_serial START 1;

CREATE TABLE comment (
	cid		int PRIMARY KEY DEFAULT NEXTVAL('comment_serial'),
        uid             int REFERENCES puser (uid),
        username        varchar(32),
        title           varchar(128),
	contents	text,
	type		varchar(128),
        cdate           timestamp with time zone --creation date
);

GRANT ALL ON comment_serial TO "www-data";
GRANT ALL ON comment_serial TO seth;

GRANT ALL ON comment TO "www-data";
GRANT ALL ON comment TO seth;
