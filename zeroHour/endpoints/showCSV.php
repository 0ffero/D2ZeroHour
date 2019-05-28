<?php
function easyArraySort(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);
}
?>
<?php
// People have been giving feed back. Some have said theyd rather have access to the CSV instead of having to enter the console numbers. Dumb arses ... just kidding ;) thanks for the feed back guys :)
// lets do this!

$included = "yes"; include('./getCurrentWeek.php');
$sortBy = $_GET["sortby"];
$order = $_GET["order"]; if ($order=="d") { $dir = SORT_DESC; } else { $dir = SORT_ASC; }
if (($order=="a" || $order=="d") && ($sortBy=="s" || $sortBy=="1" || $sortBy=="2" || $sortBy=="3")) { $continue==true; } else { echo "ERROR: POST VARS ARE INVALID"; exit; }
//$sortBy = "1"; // possible options: 1 = Terminal 1, 2 = Terminal2, 3 = Terminal 3, s = Siva Terminal Name --- USED FOR TESTING

// pre-empting needing the ability to sort on any column (coz Id want it)
$config = file_get_contents('./data/' . $weekType . 'Config.csv'); // config is now based on week type
$rows = explode("\r\n", $config);

foreach ($rows as $index => $row) {
	if ($index==0) {
		$headerArray = explode(",", $row);
	} else {
		$tmpArray = explode(",", $row);
		$sivaTerminal = trim($tmpArray[0]);
		$sivaTermNumber = trim($tmpArray[1]);
		$terminal[1] = trim($tmpArray[2]);
		$terminal[2] = trim($tmpArray[3]);
        $terminal[3] = trim($tmpArray[4]);
        foreach ($terminal as $index=> $inputs) {
            $inputArray = explode("-", $inputs);
            if (strlen($inputArray[0]) == 1) { $inputArray[0] = "0" . $inputArray[0]; }
            if (strlen($inputArray[1]) == 1) { $inputArray[1] = "0" . $inputArray[1]; }
            $terminal[$index] = implode("-", $inputArray);
        }
		$sivaArray[$sivaTermNumber] = array("SIVATerminal"=>$sivaTerminal, "Terminal 1"=>$terminal[1], "Terminal 2"=>$terminal[2], "Terminal 3"=>$terminal[3]);
	}
}

if ($sortBy == "n") { } // file is already sorted in this manner
if ($sortBy == "s") { 
    easyArraySort($sivaArray, 'SIVATerminal', $dir);
}
if ($sortBy == "1" || $sortBy == "2" || $sortBy == "3") {
    easyArraySort($sivaArray, 'Terminal ' . $sortBy, $dir);
}
if ($order=="a") { $orderArray[$sortBy]="d"; } else { $order[$sortBy]="a"; }

$outputHTML='';
foreach ($sivaArray as $dataArray) {
    $sivaTerminal = $dataArray["SIVATerminal"]; $terminal[1] = $dataArray["Terminal 1"]; $terminal[2] = $dataArray["Terminal 2"]; $terminal[3] = $dataArray["Terminal 3"];
    $cssColor = strtolower(substr($sivaTerminal,0,-1));
    foreach ($terminal as $index => $inputs) {
        $inputArray = explode("-", $inputs);
        $terminalInput = intval($inputArray[0]) . "-" . intval($inputArray[1]);
        $terminal[$index] = $terminalInput;
    }
    $completeSolution = implode(",", $terminal);
    $sortS = $orderArray["s"] ? $orderArray["s"] : "a";
    $sort1 = $orderArray["1"] ? $orderArray["1"] : "a";
    $sort2 = $orderArray["2"] ? $orderArray["2"] : "a";
    $sort3 = $orderArray["3"] ? $orderArray["3"] : "a";
    $sortR = $orderArray["r"] ? $orderArray["r"] : "a";

    $outputHTML .= '<tr><td class="solution ' . $cssColor . '" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $completeSolution . '">' . $sivaTerminal . '</td><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $completeSolution . '">' . $terminal[1] .'</td><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $completeSolution . '">' . $terminal[2] .'</td><td class="solution" data-terminal="' . $sivaTerminal . '" data-completesolution="' . $completeSolution . '">' . $terminal[3] .'</td></tr>';
}

$outputHTML = '<table class="sivaCSV" cellpadding="2" cellspacing="2"><tr><th class="orderby" data-terminal="s" data-sortby="' . $sortS . '">' . $headerArray[0] . '</th><th class="orderby" data-terminal="1" data-sortby="' . $sort1 . '">' . $headerArray[2] . '</th><th class="orderby" data-terminal="2" data-sortby="' . $sort2 . '">' . $headerArray[3] . '</th><th class="orderby" data-terminal="3" data-sortby="' . $sort3 . '">' . $headerArray[4] . '</th></tr>' . $outputHTML . '</table>';

echo $outputHTML;
?>