CREATE TABLE groupuser (
       gid   	       int REFERENCES pgroup (gid),
       uid	       int REFERENCES puser (uid)
);

GRANT ALL ON groupuser TO "www-data";
GRANT ALL ON groupuser TO seth;
