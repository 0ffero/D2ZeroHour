<?php
$included = "yes"; include('./getCurrentWeek.php');
$config = file_get_contents('./data/' . $weekType . 'Config.csv'); // config is now based on week type
$rows = explode("\r\n", $config);

foreach ($rows as $index => $row) {
	if ($index==0) {
		// headers
		$headerArray = explode(",", $row);
	} else {
		$tmpArray = explode(",", $row);
		$sivaTerminal = trim($tmpArray[0]);
		$sivaTermNumber = trim($tmpArray[1]);
		$terminal1 = trim($tmpArray[2]);
		$terminal2 = trim($tmpArray[3]);
		$terminal3 = trim($tmpArray[4]);
		$sivaArray[$sivaTermNumber] = array("SIVATerminal"=>$sivaTerminal, "Terminal 1"=>$terminal1, "Terminal 2"=>$terminal2, "Terminal 3"=>$terminal3);
	}
}
//echo "<pre>"; print_r($sivaArray); echo "</pre>"; exit;

// DEMO SETTINGS FOR SEARCH
//$terminal = 1;
//$terminalOutput = "4-12"; // arc config with duplicates
//$terminalOutput = "4-3"; // void config with duplicates
//$terminalOutput = "9-10"; // solar config with duplicates
$terminal = $_GET["terminal"]; // 1, 2 or 3
$terminalOutput = $_GET["terminaloutput"]; // looks like "4-3", "x-y" for example - format: x|y = 1-12
$terminalArray = explode("-", $terminalOutput);
$found = false;
switch ($terminal) {
	case 1: $s1l = $terminalArray[0]; $s1r = $terminalArray[1]; break;
	case 2: $s2l = $terminalArray[0]; $s2r = $terminalArray[1]; break;
	case 3: $s3l = $terminalArray[0]; $s3r = $terminalArray[1]; break;
}
$table =  '<table cellpadding="2" cellspacing="2" style="position: absolute; top: 30px; left: 350px;" class="sivaChecker"><tr><th>Terminal 1</th><th>Terminal 2</th><th>Terminal 3</th><th>Siva Terminal</th></tr>';
$table .= '<tr><td><div style="float: left;" id="s1l" class="search">' . $s1l . '</div><div style="float: left;">-</div><div style="float: left;" id="s1r" class="search">' . $s1r . '</div></td><td><div style="float: left;" id="s2l" class="search">' . $s2l . '</div><div style="float: left;">-</div><div style="float: left;" id="s2r" class="search">' . $s2r . '</div></td><td><div style="float: left;" id="s3l" class="search">' . $s3l . '</div><div style="float: left;">-</div><div style="float: left;" id="s3r" class="search">' . $s3r . '</div></td><td>Invalid Search</td></tr>';
if ( substr_count($terminalOutput, "-")==1 && strlen($terminalOutput)>=3 ) {
	foreach ($sivaArray as $sivaEndpoint) {
		if ($sivaEndpoint["Terminal " . $terminal] == $terminalOutput) {
			$found = true;
			$sivaTerminal = $sivaEndpoint["SIVATerminal"];
			$sivaRoomColor = strtolower(substr($sivaTerminal,0,-1));
			$terminal1 = $sivaEndpoint["Terminal 1"]; $terminal2 = $sivaEndpoint["Terminal 2"]; $terminal3 = $sivaEndpoint["Terminal 3"];
			$terminalString = $terminal1 . "," . $terminal2 . "," . $terminal3;
			$table .= '<tr><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $terminalString . '">' . $terminal1 . '</td><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $terminalString . '">' . $terminal2 . '</td><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $terminalString . '">' . $terminal3 . '</td><td class="' . $sivaRoomColor . ' solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $terminalString . '">' . $sivaTerminal . '</td></tr>';
		}
		$terminal1 = $terminal2 = $terminal3 = $sivaTerminal = '';

	}
}

if ($found===true) {
	$table = str_replace("Invalid Search", "", $table);
}
$table .= "</table>";

echo $table;	

?>