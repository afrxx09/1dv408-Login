1dv408-Login
============

Laboration 2, Loginsystem
Andreas Fridlund - afrxx09

Installation

Förkrav
*lokal-server eller webhotell med PHP och mysql

MySQL-databas
Skapa användare
1. Skapa en användare i databasen. Byt ut "newuser" och "password mot de uppgifter du vill använda.
```SQL
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
```
2. Ge användaren rättigheter som krävs för att kunna läsa/skriva. Byt ut "newuser" mot användarnamnet i steg 1.
```SQL
GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';
```
3. Ladda om rättigheterna på MySQL-serven så de nya läses in.
```SQL
FLUSH PRIVILEGES;
```


Skapa databas och tabell
1. Skapa en databas, förslagsvid med utf8. Antingen manuellt via valfri klient eller med förljande SQL
```SQL
CREATE DATABASE IF NOT EXISTS `login` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `login`;
```

2. Skapa en tabell för användare:
```SQL
CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL DEFAULT '',
  `password` VARCHAR(40) NOT NULL DEFAULT '',
  `token` VARCHAR(40) NOT NULL DEFAULT '',
  `ip` VARCHAR(15) NOT NULL DEFAULT '',
  `agent` VARCHAR(200) NOT NULL DEFAULT '',
  `cookietime` VARCHAR(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username` (`username`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;
```

Aktivera mod_rewrite i webservern.
För WAMP med apache(v2.4.9) webserver:
1. Leta upp filen httpd.conf
(\wamp\bin\apache\apache-2.4.9\conf\httpd.conf)
2. Leta upp Raden:
```
"#LoadModule rewrite_module modules/mod_rewrite.so"
```
Ta bort första tecknet(#) så det står:
```
"LoadModule rewrite_module modules/mod_rewrite.so"
```
3 Spara och stäng filen.
4 Starta om webservern
För andra webservrar:
Googla på "activate mod_rewrite on xxx web server" där xxx är namnet på webservern du använder så bör du enkelt hitta instruktioner på hur du aktiverar det.


Ladde upp och konfigurera källkoden.
1. Gå in i filen config.php (\cfg\config.php)
Här finns generella inställningar för applikationen och de 4 inställningarna för databasåtkomst måste redigeras
2. Bland de översta raderna finns följande kod:
```PHP
define('DB_HOST', '127.0.0.1');
define('DB_DATABASE', 'login');
define('DB_USERNAME', 'afrxx09');
define('DB_PASSWORD', 'lnustudent');
```
Ändra dessa så de stämmer med uppgifterna Från steg 1 när databas och användare skapades.
DB_HOST är ipnummer eller domännamn till MySQL-servern. Beroende på inställningar på servern kan det variera men troligtvis ska det vara '127.0.0.1' eller 'localhost'
DB_DATABASE är namnet på databasen som skapades tidigare
DB_USERNAME är användarnamnet på användaren som skapades tidigare
DB_PASSWORD är lösenordet till den användaren som skapades tidigare.

3. Ladda upp filerna / lägg dem i den katalog på webvservern som du önskar köra applikationen från.
