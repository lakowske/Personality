CREATE SEQUENCE comment_permission_serial START 1;
CREATE TABLE comment_permission ( 
        --create the personality permission table
	comment_permission_id	     		 int PRIMARY KEY DEFAULT NEXTVAL('comment_permission_serial'),
	permid					 int REFERENCES permission (permid),
	uid					 int REFERENCES puser (uid),
);

GRANT ALL ON comment_permission_serial TO "www-data";
GRANT ALL ON comment_permission_serial TO prefedit;
GRANT ALL ON comment_permission_serial TO seth;

GRANT ALL ON comment_permission TO "www-data";
GRANT ALL ON comment_permission TO prefedit;
GRANT ALL ON comment_permission TO seth;