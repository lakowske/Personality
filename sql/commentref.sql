CREATE TABLE commentref (
	cid		int REFERENCES comment (cid),
	rcid		int REFERENCES comment (cid)
);


GRANT ALL ON commentref TO "www-data";
GRANT ALL ON commentref TO seth;
