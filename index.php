<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Тест</title>
	</head>

	<body>

		<?php
		require_once('stringfilter.php');

		$s = new StringFilter("<span>Hello</span> <i>W</i><br />orl<b>d</b><u>!</u>", "/<[^>]*>/si");

		echo "substring: ".$s->substring(5, 6)."<br><br>";
		echo "concat: ".$s->concat("<i>!!</i><i>6</i>")."<br><br>";
		echo "replace: ".$s->replace('!!!', '<b>@@@</b>')."<br><br>";
		echo "string: ".$s->getString()."<br><br>";
		echo "string-html: ".$s->getStringToHtml()."<br><br>";

		?>

	</body>

</html>
