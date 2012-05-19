--A flexible table that can hold permission values for objects and tables
CREATE SEQUENCE permission_serial START 1;
CREATE TABLE permission ( 
        --create the personality permission table
	permid	     		 int PRIMARY KEY DEFAULT NEXTVAL('permission_serial'),
	objectid		 int,
	pname			 varchar(256),
	pvalue			 varchar(256)
);

GRANT ALL ON permission_serial TO "www-data";
GRANT ALL ON permission_serial TO prefedit;
GRANT ALL ON permission_serial TO seth;

GRANT ALL ON permission TO "www-data";
GRANT ALL ON permission TO prefedit;
GRANT ALL ON permission TO seth;