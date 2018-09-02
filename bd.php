<?php

define("DS", DIRECTORY_SEPARATOR);
$sitePath = realpath(dirname(__FILE__). DS) .DS;
define("SITE_PATH", $sitePath);

$config = file_get_contents(SITE_PATH . DS . "config.xml");
$configXML = new SimpleXMLElement($config);

$host     = (string)$configXML->db->host;
$dbName   = (string)$configXML->db->dbname;
$username  = (string)$configXML->db->username;
$password = (string)$configXML->db->password;

// вывод всех книг жанра фантастика
echo "---------ВСЕ КНИГИ ЖАНРА ФАНСТАСТИКА------<br/>";
$PDO = new PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8', $username, $password);
$query = $PDO->prepare('SELECT id_author, description, authors.name as name, authors.surname as surname FROM books INNER JOIN authors ON books.id_author = authors.id WHERE books.ganre = "Фантастика"');
$query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo " Книга: ".$row['description'].". Автор: ".$row['name']." ".$row['surname']."<br/>";
}

// вывод автора у которого больше всего книг
echo "---------АВТОР С НАИБОЛЬШИМ КОЛИЧЕСТВОМ КНИГ------<br/>";

$PDO = new PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8', $username, $password);
$query = $PDO->prepare('SELECT COUNT(books.ISBN) as ISBN, authors.name as name, authors.surname as surname FROM books INNER JOIN authors ON books.id_author = authors.id GROUP BY authors.name, authors.surname ORDER BY COUNT(books.ISBN) DESC LIMIT 0,1');
$query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "Автор: ".$row['name']." ".$row['surname']."<br/>";
}


