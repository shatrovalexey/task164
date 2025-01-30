<?php
$db_connection_config = [
	'dsn' => 'mysql:dbname=task164'
	, 'login' => 'root'
	, 'passwd' => 'f2ox9erm'
	, 'charset' => 'utf8',
];

$dbh = getDBH($db_connection_config);

global $db_connection_config, $dbh;

function getDBH(array $db_connection_config): \PDO
{
	$dbh = new \PDO($db_connection_config['dsn'], $db_connection_config['login'], $db_connection_config['passwd']);
	$dbh->query("SET NAMES {$db_connection_config['charset']};");

	return $dbh;
}

function sql_execute(\PDO $dbh, string $sql, array $args = [], bool $result = false)
{
	$sth = $dbh->prepare($sql);
	$sth->execute($args);

	return $result ? $sth->rowCount() : $sth;
}