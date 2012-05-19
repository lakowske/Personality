CREATE SEQUENCE file_serial START 1;
CREATE TABLE file ( 
        --create the personality user table
	fid	     		 int PRIMARY KEY DEFAULT NEXTVAL('file_serial'),
	uid		    	 int REFERENCES puser (uid),
        gid                 	 int REFERENCES pgroup (gid),
	fpath		    	 varchar(256) UNIQUE,
	filename		 varchar(256) NOT NULL,
	origname		 varchar(256) NOT NULL,
       	create_date	    	 timestamp with time zone
);

GRANT ALL ON file_serial TO "www-data";
GRANT ALL ON file_serial TO prefedit;
GRANT ALL ON file_serial TO seth;

GRANT ALL ON file TO "www-data";
GRANT ALL ON file TO prefedit;
GRANT ALL ON file TO seth;