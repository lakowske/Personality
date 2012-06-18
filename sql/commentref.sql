CREATE SEQUENCE commentref_serial START 1;

CREATE TABLE commentref (
       refid 		int PRIMARY KEY DEFAULT NEXTVAL('commentref_serial'),
       cid		int REFERENCES comment (cid),
       rcid		int REFERENCES comment (cid)
);

GRANT ALL ON commentref_serial TO "www-data";
GRANT ALL ON commentref_serial TO seth;

GRANT ALL ON commentref TO "www-data";
GRANT ALL ON commentref TO seth;
