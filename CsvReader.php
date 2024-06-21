<?php

class CsvReader
{
	private $file = null;
	private $delimiter = null;

	function __construct($file, $delimiter)
	{
		$this->file = $file;
		$this->delimiter = $delimiter;
	}

	//Считывает csv файл и возвращает массив пользователей
	public function read()
	{
		$users = [];
		if (($open = fopen($this->file, "r")) !== false) {
			while (($data = fgetcsv($open, 1000, $this->delimiter)) !== false) {
				$users[$data[0]] = $data[1];
		}
			
			fclose($open);
		}
		return $users;
	}
}

?>
