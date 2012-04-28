CREATE SEQUENCE permission_serial START 1;
CREATE TABLE permission ( 
        --create the personality user table
	permid	     		 int PRIMARY KEY DEFAULT NEXTVAL('permission_serial'),
	pread			 boolean,
	pwrite			 boolean
);

GRANT ALL ON permission_serial TO "www-data";
GRANT ALL ON permission_serial TO prefedit;
GRANT ALL ON permission_serial TO seth;

GRANT ALL ON permission TO "www-data";
GRANT ALL ON permission TO prefedit;
GRANT ALL ON permission TO seth;