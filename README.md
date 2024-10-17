# Fruits Shop

![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)\
![wakatime](https://wakatime.com/badge/github/csouza1995/frutas-php.svg)

## History
Thats project following scopes of a technical test applied on a recruitment interview.\
The propose is watching how I work with PHP without frameworks during a time when I doing the a list of tasks.

## Tasks
1️. Set up a database with the name 'loja' using tools such as PHPmyadmin, Workbench, DBBeaver, etc.\
2️. Using SQL commands, create a table named 'frutas' containing the fields: id, fruta_nome, fruta_valor, criado_em, removido_em.\
3️. Create record of at least 3 fruits using SQL command or via PHP.\
4️. List records into a html table within a PHP file.\
5️. Add a search bar to filter table records.\
6️. Add a functional button that makes a record as removed.\

I had 2 more tasks that I can't remember!🤦🏽‍♂️

## SQL
Creating table:
~~~~sql
CREATE TABLE frutas (
	id BIGINT NOT NULL AUTO_INCREMENT,
	fruta_nome VARCHAR(255) NOT NULL,
	fruta_valor DECIMAL(10,4) NOT NULL,
	criado_em DATETIME NOT null DEFAULT CURRENT_DATE(),
	removido_em DATETIME DEFAULT NULL,
	
	PRIMARY KEY(id),
	UNIQUE(fruta_nome)
);
~~~~

Inseting records:
~~~~sql
INSERT INTO frutas 
	(fruta_nome, fruta_valor)
VALUES 
	("Banana", 1.49), 
	("Maça", 0.99), 
	("Pera", 1.95);
~~~~

---
Thanks [Ileriayo Adebiyi](https://github.com/Ileriayo) for repository of [Markdown Badges](https://github.com/Ileriayo/markdown-badges)
