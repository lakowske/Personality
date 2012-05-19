CREATE SEQUENCE groupuser_serial START 1;

CREATE TABLE groupuser (
       guid  	       int PRIMARY KEY DEFAULT NEXTVAL('groupuser_serial'),
       gid   	       int REFERENCES pgroup (gid),
       uid	       int REFERENCES puser (uid)
);

GRANT ALL ON groupuser TO "www-data";
GRANT ALL ON groupuser TO seth;
