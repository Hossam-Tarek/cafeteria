CREATE USER "cafeteria"@"localhost" IDENTIFIED BY "itiProject-2020";

GRANT ALL PRIVILEGES ON cafeteria . * TO "cafeteria"@"localhost";

FLUSH PRIVILEGES;
