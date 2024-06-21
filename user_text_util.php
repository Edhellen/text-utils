<?php

require 'CsvReader.php';
require 'TextUtils.php';

const CSV_FILE_NAME = './people.csv';
const TEXTS_PATH = './texts';
const COMMA_DELIMITER = ',';
const SEMICOLON_DELIMITER = ';';

// Получение аргументов командной строки
if ($argc !== 3) {
	echo "Invalid count of parameters\n";
	exit(1);
}

$delimiter = $argv[1];
$task = $argv[2];

// Определение разделителя для CSV
if ($delimiter != 'comma' && $delimiter != 'semicolon') {
	echo "Invalid delimiter\n";
	exit(1);
}

$csvDelimiter = null;

if ($delimiter === 'comma') {
	$csvDelimiter = COMMA_DELIMITER;
}
else if ($delimiter === 'semicolon'){
	$csvDelimiter = SEMICOLON_DELIMITER;
}

// Чтение CSV файла
$csvFile = CSV_FILE_NAME;
$csvReader = new CsvReader($csvFile, $csvDelimiter);
$users = $csvReader->read();

// Обработка текста
$text = new TextUtils($users, TEXTS_PATH);

switch ($task) {
	case 'countAverageLineCount':
		$text->countAverageLines();
		break;
	case 'replaceDates':
		$text->replaceAllDates();
		break;
	default:
		echo "Error\n";
		exit(1);
}

?>