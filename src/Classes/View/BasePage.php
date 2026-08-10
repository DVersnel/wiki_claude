<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class BasePage implements iView
{
	protected array $elements;
	
	public function __construct(array $elements)
	{
		$this->elements = $elements;
	}

	public function show()
	{
		$this->openDoc();
		$this->openBody();
        $this->showElements();
		$this->closeBody();
        $this->closeDoc();
	}
	
	protected function openDoc()
	{
		echo '<!DOCTYPE html>
			<html lang="en" data-bs-theme="dark">
			<head>
			<title>Bootstrap Example</title>
			<meta charset="utf8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
			<script src="src/jquery.js"></script>
			<script src="Scripts/Ratings.js"></script>
			<script src="Scripts/TagsSelect.js"></script>
			</head>';
	}

	protected function openBody()
	{
		echo '<body class="bg-body-tertiary">';
	}

	protected function closeBody()
	{
		echo '</body>';
	}
	
	protected function showElements()
	{
		foreach ($this->elements as $element) 
		{
            $element->show();
        }
	}
	
	protected function closeDoc()
	{
		echo '</html>';
	}
}