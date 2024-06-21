<?php

class TextUtils
{
	private $users = null;
	private $textDir = null;
	private $declaredOutputDir = './output_texts';

	function __construct($users, $textDir)
	{
		$this->users = $users;
		$this->textDir = $textDir;
	}

	//Считает среднее количество строк в текстовых файлах для каждого пользователя.
	//Выводит имя пользователя и среднее количество строк на экран.
	public function countAverageLines()
	{
		foreach ($this->users as $id => $name) {
			$files = glob("{$this->textDir}/{$id}-*.txt");
			$totalLines = 0;
			$fileCount = count($files);

			foreach ($files as $file) {
				$lines = file($file);
				$totalLines += count($lines);
			}

			$averageLines = $fileCount ? $totalLines / $fileCount : 0;
			echo "{$name}: Average lines = {$averageLines}\n";
		}
	}
	
	//Заменяет дату в переданном файле
	private function replaceDatesInFile($content, &$totalReplacements)
	{
		$pattern = '/(\d{1,2})\/(\d{1,2})\/(\d{1,2})/';
		$matches = [];

		preg_match_all($pattern, $content, $matches);

		foreach ($matches[0] as $match) {
			$date = DateTime::createFromFormat('d/m/y', $match);
			if ($date !== false) {
				$formattedDate = $date->format('m-d-Y');
				$content = str_replace($match, $formattedDate, $content);
				$totalReplacements++;
			}
		}

		return $content;
	}

	//Заменяет даты во всех текстах и сохраняет измененные тексты в папку 
	public function replaceAllDates()
	{
		$outputDir = $this->declaredOutputDir;
		if (!is_dir($outputDir)) {
			mkdir($outputDir);
		}

		foreach ($this->users as $id => $name) {
			$files = glob("{$this->textDir}/{$id}-*.txt");
			$totalReplacements = 0;

			foreach ($files as $file) {
				$content = file_get_contents($file);
				$newContent = $this->replaceDatesInFile($content, $totalReplacements);

				$outputFile = "{$outputDir}/" . basename($file);
				file_put_contents($outputFile, $newContent);
			}

			echo "{$name}: Total date replacements = {$totalReplacements}\n";
		}
	}
}

?>
