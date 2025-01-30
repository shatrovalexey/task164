<?php
include_once('config.php');

$data = [];
$response = [];

$dbh = getDBH($db_connection_config);

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = null;

if (in_array($_REQUEST['action'], ['create', 'update',])) {
	$data = [
		'name' => $_REQUEST['name']
		, 'phone'  => $_REQUEST['phone']
		, 'email' => $_REQUEST['email']
	];
}

switch ($_REQUEST['action']) {
	case 'create': {
		$response['success'] = sql_execute($dbh, '
INSERT IGNORE INTO
	`record`
SET
	`name` := :name
	, `phone` := :phone
	, `email` := :email
;
		', $data, true);

		break;
	}
	case 'update': {
		$response['success'] = sql_execute($dbh, '
UPDATE IGNORE
	`record` AS `r1`
SET
	`r1`.`name` := :name
	, `r1`.`phone` := :phone
	, `r1`.`email` := :email
;
		', $data, true);

		break;
	}
	case 'delete': {
		$response['success'] = sql_execute($dbh, '
DELETE IGNORE
	`r1`.*
FROM
	`record` AS `r1`
WHERE
	(`r1`.`email` = :email);
		', [':email' => $_REQUEST['email'],], true);

		break;
	}
	default: {
		$response['data'] = sql_execute($dbh, '
SELECT
	`r1`.`name`
	, `r1`.`phone`
	, `r1`.`email`
FROM
	`record` AS `r1`
;
		')->fetchAll(\PDO::FETCH_ASSOC);
	}
}

header('Content-Type: application/json');

echo json_encode($response);