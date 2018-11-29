DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
  role           VARCHAR(32),
  description    VARCHAR(128),
  PRIMARY KEY (role)
);


DROP TABLE IF EXISTS role_inherits;
CREATE TABLE role_inherits (
  role           VARCHAR(32),
  inherited_role VARCHAR(128),
  PRIMARY KEY (role, inherited_role),
  FOREIGN KEY (role)           REFERENCES roles(role),
  FOREIGN KEY (inherited_role) REFERENCES roles(role)
);


DROP TABLE IF EXISTS resources;
CREATE TABLE resources (
  resource       VARCHAR(32),
  description    VARCHAR(128),
  PRIMARY KEY (resource)
);


DROP TABLE IF EXISTS privileges;
CREATE TABLE privileges (
  privilege      VARCHAR(32),
  resource       VARCHAR(32),
  description    VARCHAR(128),
  PRIMARY KEY (resource, privilege),
  FOREIGN KEY (resource)       REFERENCES resources(resource)
);


DROP TABLE IF EXISTS role_privileges;
CREATE TABLE role_privileges (
  role           VARCHAR(32),
  resource       VARCHAR(32),
  privilege      VARCHAR(32),
  PRIMARY KEY (role, resource, privilege),
  FOREIGN KEY (role)           REFERENCES roles(role),
  FOREIGN KEY (resource)       REFERENCES resources(resource),
  FOREIGN KEY (privilege)      REFERENCES privileges(privilege)
);