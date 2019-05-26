<?php
// INITIALISE VARIABLES
$rn = "\r\n";
$pathHW = 50;

$included = "yes"; include('./getCurrentWeek.php');

// SET UP COLOURS FOR EACH WEEK TYPE (VOID, ARC, SOLAR)
$colorVoidArray  = array("000","8500B2","B800F4","CB2FFF","DC71FF"); $nonPathTileVoid = array("4B0097","9326FF");
$colorArcArray   = array("000","00A0E6","00BFFF","3CCEFF","82E0FF","3CCEFF"); $nonPathTileArc = array("156595","00A3D9");
$colorSolarArray = array("000","B20000","FD3402","FF9326","FFC926","FF9326","FFC926","FF9326","FD3402","B20000"); $nonPathTileSolar = array("EE0000","FFD24D");
$colorArray = array($colorVoidArray, $colorArcArray, $colorSolarArray);
$cssTileWeekArray = array($nonPathTileVoid, $nonPathTileArc, $nonPathTileSolar);
$colorListArray = $colorArray[$weekOffset]; // get this weeks color scheme
$cssTileColorArray = $cssTileWeekArray[$weekOffset]; $cssBGColor = $cssTileColorArray[0]; $cssBorderColor = $cssTileColorArray[1];

?><head>

<script type="text/javascript" src="/js/jquery331.js"></script>
<script type="text/javascript" src="/js/colorAnimator.js"></script>

<style type="text/css">
    body { background-color: black; }
    body, div, table, td { padding: 0px; margin: 0px; font-family: monospace; } /* basic catch-all */
    #dateContainer { font-size: 18px; color: white; }
        .weekType { clear: both; background-color: #<?php echo $cssBGColor; ?>; margin: 0px auto; width: <?php echo $pathHW*5; ?>px; padding: 0px; text-align: center; float: left; padding-top: 5px; padding-bottom: 5px; text-shadow: 1px 1px 3px black; }
        .dateBox { width: <?php echo $pathHW*5/7-2; ?>px; background-color: <?php echo $cssBGColor; ?>; border: 1px solid black; box-shadow: inset 0px 1px 8px 2px black; float: left; padding-top: 10px; padding-bottom: 10px; text-align: center; }
        .highlight { text-shadow: 2px 2px 2px black; }
    #floorContainer { width: <?php echo $pathHW*5; ?>px; height: <?php echo $pathHW*6; ?>px; border: 1px solid black; }
    #floorContainer .path, #floorContainer .empty { width: <?php echo $pathHW-2; ?>px; height: <?php echo $pathHW-2; ?>px; float: left; }
    #floorContainer .empty { background-color: #<?php echo $cssBGColor; ?>; box-shadow: inset 0px 1px 8px 2px black; border: 1px solid #<?php echo $cssBorderColor; ?>; }
    #floorContainer .path { background-color: black; border: 1px solid white; box-shadow: inset 0px 1px 7px 4px whitesmoke; z-index: 10; }
</style>
</head>

<body>

<div id="dateContainer">
<?php
    // Set up the date bar
    foreach ($weekArray as $dayNumber) {
        if ($dayNumber == $currentDayNum) { $css = 'dateBox highlight'; } else { $css='dateBox'; }
        echo '<div class="' . $css . '">' . $dayNumber . '</div>';
    }
    echo '<div class="weekType">' . ucfirst($rotationArray[$weekOffset]) . ' week</div>' . $rn;
?>
</div>

<?php
// SET UP VAULT PATH BASED ON CURRENT WEEK OFFSET
// Load up the path data
$paths = file_get_contents('./data/paths.map');
$tilePuzzleArray = explode(',', $paths);
$todaysPuzzle = str_split($tilePuzzleArray[$weekOffset]);

// This piece of code allows for 35 moves (most used is 16)
$pathLengthFound = false;
for($i=0; $i<10; $i++) { $lengthArray[$i] = $i; }
for($i=10; $i<=35; $i++) { // 35 includes 0-9 and A-Z
    $currentAlpha = chr(65 + ($i-10));
    $lengthArray[$currentAlpha] = $i;
}

// Build the Floor Layout HTML
$outputHTML = '<div id="floorContainer">';
foreach ($todaysPuzzle as $puzzlePiece) {
    if ($puzzlePiece == "0") {
        $outputHTML .= '<div class="empty">&nbsp;</div>';
    } else {
        if ($pathLengthFound == false) {
            $pathLength = $lengthArray[$puzzlePiece];
            $pathLengthFound = true;
        }
        $currentPathPosition = $lengthArray[$puzzlePiece];
        $outputHTML .= '<div class="path pathPos' . $currentPathPosition . '">&nbsp;</div>';
    }
}
$outputHTML .= '</div>';
echo $outputHTML;

$colorListInfo = ucfirst($rotationArray[$weekOffset]) . " week colours being used";
?>
<div id="vars" data-colorlist="<?php echo implode(",", $colorArray); ?>" data-colourrotation="<?php echo $colorListInfo; ?>"></div>
</body>

<script type="text/javascript">
    function animatePath () {
        <?php
        // create script that will animate all the path tiles
        $animationJS = '';
        $delay = 200; $totalDelay = 0; $longestDelay = 0;
        $functionJS = '';
        for ($i=1; $i<($pathLength+1); $i++) {
            $introDelay = ($i-1) * $delay;
            $animationJS .= '$(".pathPos' . $i . '").stop().delay(' . $introDelay . ')';
            $totalDelay += $introDelay;

            foreach ($colorListArray as $color) {
                $animationJS .='.animate({ backgroundColor: "#' . $color . '"}, ' . $delay . ')';
                $totalDelay += $delay;
            }
            if ($i==$pathLength) { // final animation set found
                $finalCall = ' animatePath();';
                $finalCall = ' console.log("All animations have finished");';
            } else {
                $finalCall = '';
            }
            $animationJS = substr($animationJS, 0,-1) . ', function() { console.log("Animation ' . $i . ' Complete");' . $finalCall . ' })';
            if ($i!=1) {
                echo "\t\t";
            }
            echo 'animation' . $i . '();' . $rn;
            $functionJS .= 'function animation' . $i . '() {' . $rn;
            $functionJS .= "\t" . 'console.log("Starting Animation ' . $i . ' -- Duration: ' . $totalDelay . 'ms");' . $rn;
            $functionJS .= "\t" . $animationJS . $rn;
            $functionJS .= '}' . $rn;
            if ($totalDelay>$longestDelay) { $longestDelay = $totalDelay; }
            $animationJS=''; $totalDelay=0;
        }
        ?>
    }

    <?php echo $functionJS; ?>
</script>

<script type="text/javascript">
$(document).ready(function(){
    animatePath();
    setInterval( function() { console.clear(); animatePath(); }, <?php echo $longestDelay + 1000; ?>);
})
</script>

</html>