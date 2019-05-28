<?php
$searchTable =  '<table cellpadding="2" cellspacing="2" style="position: absolute; top: 30px; left: 350px;" class="sivaChecker"><tr><th>Terminal 1</th><th>Terminal 2</th><th>Terminal 3</th><th>Siva Terminal</th></tr>';
$searchTable .= '<tr><td><div style="float: left;" id="s1l" class="search"></div><div style="float: left;">-</div><div style="float: left;" id="s1r" class="search"></div></td><td><div style="float: left;" id="s2l" class="search"></div><div style="float: left;">-</div><div style="float: left;" id="s2r" class="search"></div></td><td><div style="float: left;" id="s3l" class="search"></div><div style="float: left;">-</div><div style="float: left;" id="s3r" class="search"></div></td><td class="sivaOutput"></td></tr>';
$searchTable .= "</table>";

$roomColors = array("green", "white", "yellow", "red", "purple", "blue", "cyan");

$solutionTable = '<table style="float: left;" cellpadding="2" cellspacing="2" class="sivaSolution"><tr><th>Solution #</th><th>Siva Room/Terminal</th></tr>';
for($room=1; $room<=7; $room++) {
	for ($terminalNumber=1; $terminalNumber<=7; $terminalNumber++) {
		$thisTerminal = $terminalNumber + (($room-1)*7);
		$solutionTable .= '<tr><td>' . $thisTerminal . '</td><td id="solution' . $thisTerminal . '" class="solutionTable"></td></tr>';
	}
}
$solutionTable .= '</table>';

// build the siva output panels
$dialArray = array(2=>12, 3=>1, 0=>11, 4=>2, 12=>10, 17=>3, 18=>9, 23=>4, 24=>8, 28=>5, 32=>7, 33=>6);
$inputPanelHTML = '<tr>';
for ($i=0; $i<36; $i++) {
	if ($i%6==0 && $i>0) {
		$inputPanelHTML .= '</tr>' . "\r\n" . '<tr>';
	}
	if ($dialArray[$i]) {
		$clockNum = $dialArray[$i]; $class = ' class="clickable" ';
	} else {
		$clockNum = '&nbsp;'; $class = ' class="none" ';
	}

	if ($i==0 || $i==4 || $i==24 || $i==28) {
		$colspan = ' colspan="2"'; $rowspan = 'rowspan="2"';
	} else {
		$colspan = ''; $rowspan = '';
	}

	if ($i==1 || $i==5 || $i==6 || $i==7 || $i==10 || $i==11 || $i==25 || $i==29 || $i==30 || $i==31 || $i==34 || $i==35) {
		// currently looks like it does nothing, but this empty line stops the TD being drawn
	} else {
		$inputPanelHTML .= '<td' . $class . $colspan . $rowspan . '>' . $clockNum . '</td>';
	}
	
}
$inputPanelHTML .= '</tr>' . "\r\n";
$inputPanelHTML = '<table class="inputPanel">' . $inputPanelHTML . '</table>';

?><html>
<head>
	<script type="text/javascript" src="/js/jquery331.js"></script>
	<script type="text/javascript" src="./js/draggable.js"></script>

	<style type="text/css">
		body { font-size: 18px; background-color: black; color: white; padding: 0px; margin: 0px; }
		#container { width: 1800px; padding: 0px; margin: 0px; }
		img { border: none; padding: 0px; margin:0px; }
		#overviewContainer { width: 1011px; height: 1058px; padding: 0px; margin: 0px; }
		.highlight { outline-color: white; }

		td { color: white; }
		div.green, td.green { background-color: green }
		div.white, td.white { background-color: white; color: black; }
		div.yellow, td.yellow { background-color: yellow; color: black; }
		div.red, td.red { background-color: red; }
		div.cyan, td.cyan { background-color: cyan; color: black; }
		div.blue, td.blue { background-color: blue; }
		div.purple, td.purple { background-color: purple; }
		div.siva { background-color: white; color: black; }

		#siva1Terminal, #siva2Terminal, #siva3Terminal { clear: left; }
			#siva1Terminal { position: absolute; top: 180px; left: 270px; }
			#siva2Terminal { position: absolute; top: 662px; left: 470px;}
			#siva3Terminal { position: absolute; top: 740px; left: 270px;}
		.sivaTerminal .left, .sivaTerminal .right { float: left; }
		.sivaTerminal .right { position: relative; top: 75px; }
		table.inputPanel { background-image: url('./images/sivaTerminalsComplete.png'); border-spacing: 0px; }
		table.inputPanel td { width: 22px; height: 22px; text-align: center; padding: 0px; margin: 0px; }
		.clickable { cursor: pointer; color: white; color: rgba(0, 0, 0, 0.0); }
		.solution { cursor: pointer; }

		.createdDiv { background-color: green; margin: 0px; padding: 0px; }
		.sivaChecker, .sivaSolution, .sivaCSV { background-color: #1a3e5e; }
		.sivaCSV { font-size: 12px; }
		.sivaChecker td, .sivaSolution td, .sivaCSV td {  border: 1px solid black; }
		.sivaSolution { position: absolute; top: 0px; left: 1050px; font-size: 12px; }

		#csvTable { position: absolute; top: 0px; left: 1250px; }
		#csvTable td { text-align: right; }
	</style>
</head>
		
<body>
	<div id="container">
		<div id="siva-map"></div>	
		<div id="overviewContainer"><img src="./images/puzzleOverview.png"></div>
		<?= $searchTable; ?>
		<div id="siva1Terminal" class="sivaTerminal">
			<div class="left"><?= $inputPanelHTML; ?></div>
			<div class="right"><?= $inputPanelHTML; ?></div>
		</div>
		<div id="siva2Terminal" class="sivaTerminal">
			<div class="left"><?= $inputPanelHTML; ?></div>
			<div class="right"><?= $inputPanelHTML; ?></div>
		</div>
		<div id="siva3Terminal" class="sivaTerminal">
			<div class="left"><?= $inputPanelHTML; ?></div>
			<div class="right"><?= $inputPanelHTML; ?></div>
		</div>
		<?= $solutionTable; ?>
		<div id="csvTable"></div>
	</div>
	<div id="vars" data-flashing="0" data-week="" data-terminal="" data-oldterminal="" data-solutionnumber="0"></div>
</body>
				
<script type="text/javascript">
    var flashTimer = setInterval( function() { // the order of shit cant be important
		oldTerminal = $('#vars').data('oldterminal').trim();
		if (oldTerminal != "") {
			$('#vars').data('oldterminal','');
			$('#' + oldTerminal).stop().fadeOut(1);
		}

		currentTerminal = $('#vars').data('terminal').trim();
		if ($('#vars').data('flashing')==1) {
			if (currentTerminal !="") {
				$('#' + currentTerminal).fadeIn(333).fadeOut(333);
				$('#vars').data("flashing", 1);
			}
		} else {
			if ($('#vars').data('terminal').trim() !="") {
				$("#" + $('#vars').data('terminal')).fadeOut(1);
				$('#vars').data("terminal",'');
			}
		}
	}, 1000)
	
	$(document).ready(function(){
		$.get('./endpoints/getCurrentWeek.php', function(weekType) { $("#vars").data("week", weekType) }) // returns solar, arc or void
		$.get('./endpoints/showCSV.php?sortby=1&order=a', function(response) {
			$('#csvTable').html(response);
		})
		loadDivMap();
		$("#container").on( "mouseenter", ".clickable", function() { // animate siva output terminals
			offset = parseInt($(this).html()) * 134;
			$(this).closest(".inputPanel").css("background-position-x", -offset);
		})

		$("#container").on( "click", ".clickable", function() {
			// siva terminal input
			$('#vars').data("flashing", 0);
			sivaTerminal = $(this).closest(".sivaTerminal").attr("id");
			position = $(this).closest("div").attr("class");
			inputNumber = $(this).html();
			switch (sivaTerminal) {
				case 'siva1Terminal': terminalEndpoint = "s1"; break;
				case 'siva2Terminal': terminalEndpoint = "s2"; break;
				case 'siva3Terminal': terminalEndpoint = "s3"; break;
			}
			if (position=="left") { terminalEndpoint +="l"; } else { terminalEndpoint +="r"; }
			$("#" + terminalEndpoint).html(inputNumber);

			// check for more than one input
			searchData = ''; searchDataObject = {};
			$(".search").each( function() { 
				grabbedData = $(this).html().trim();
				sivaTerminal = $(this).attr("id");
				if (grabbedData.length > 0) {
					searchData += sivaTerminal + "," + grabbedData + ","; // originally passed this string to the sivaDBChecker but decided to send it as separate vars
					searchDataObject[sivaTerminal] = grabbedData;
				}
			})
			if (Object.keys(searchDataObject).length >1) { // we have enough data to do a search
				// Im only allowing a single terminal search just now. The reason for this is that if you check Terminal 1, the chances of getting two options is low (something like 15%?)
				// So, basically a search for "Terminal 1" left = 4, right = 3 has about an 85% chance of bringing back a single answer (although 4,3 on terminal 1 is a perfect example of multiple options for testing purposes)
				if (searchDataObject.s2l && searchDataObject.s2r) { terminal = 2; terminalOutput = searchDataObject.s2l  + "-" + searchDataObject.s2r; } // Terminal 2 - low priority - Can return many results
				if (searchDataObject.s3l && searchDataObject.s3r) { terminal = 3; terminalOutput = searchDataObject.s3l  + "-" + searchDataObject.s3r; } // Terminal 3 - medium priority - Medium to high chance of
				if (searchDataObject.s1l && searchDataObject.s1r) { terminal = 1; terminalOutput = searchDataObject.s1l  + "-" + searchDataObject.s1r; } // Terminal 1 - high priority - Low chance of multiple reults

				if (Number.isInteger(terminal)) {
					$('.sivaOutput').html("Searching...");
					$.get( "./endpoints/searchSivaDatabase.php", { terminal: terminal, terminaloutput: terminalOutput }, function( response ) {
						$('.sivaChecker').replaceWith(response);
						// check if any of these returned options have already been used
						solutionString = '';
						$('.solutionTable').each( function() {
							solutionHTML = $(this).html().trim();
							if (solutionHTML!="") {
								solutionString += solutionHTML + ",";
							}
						})
						$('.solution').each( function() {
							completeSolution = $(this).data('completesolution');
							if (completeSolution!='') {
								if (solutionString.includes(completeSolution)) {
									$(this).fadeOut(1);
								}
							}
						})
					});
				}
			}
		})

		$("#container").on( "click", ".solution", function() {
			// a solution has been selected reset all html input terminals
			$('.search').html('');

			solutionTerminal = $(this).data("terminal"); solution = $(this).data('completesolution');
			cssColor = solutionTerminal.substr(0, solutionTerminal.length-1).toLowerCase();
			solutionNumber = parseInt($('#vars').data('solutionnumber')) + 1;
			$('.solution').each ( function() {
				if ($(this).data('completesolution') == solution) { $(this).fadeOut(1); }
			})
			$("#solution" + solutionNumber).html(solutionTerminal + " (" + solution + ")").addClass(cssColor);
			$("#solution" + solutionNumber).data('sivaopterminal', solution);
			$('#vars').data('solutionnumber', solutionNumber);

			divName = solutionTerminal.toLowerCase();
			className = solutionTerminal.substr(0, solutionTerminal.length-1);
			if ($('#vars').data('terminal')!="") { 
				$("#vars").data('oldterminal', $('#vars').data('terminal'));
				$('#vars').data('terminal','');
			}
			$('#vars').data('terminal', divName).data('flashing', 1);
		})

		$("#container").on( "click", ".orderby", function() {
			order = $(this).data('sortby');
			terminalName = $(this).data('terminal');
			solutionsArray = [];
			$.get('./endpoints/showCSV.php?sortby=' + terminalName + '&order=' + order, function(response) {
				$('.sivaCSV').replaceWith(response);
				$('.solutionTable').each( function() {
					solution = $(this).html();
					if (solution!="") {
						solutionArray = solution.replace(')','').split('(');
						solutionsArray.push(solutionArray[1]);
					}
				})
				$(solutionsArray).each( function() {
					currentSolution = this;
					$('.solution').each( function() {
						if ($(this).data('completesolution')==currentSolution) {
							$(this).fadeOut(1);
						}
					})
				})
			})
		})
    })

	function createDiv(name, x = 0, y = 0, width = 60, height = 30, rotation = 0, static = false) {
		varsList = "name: " + name + ", left: " + x + ", top: " + y + ", width: " + width + "px, height: " + height + "px, rotation: " + rotation + "deg";
		cssColor = name.substr(0, name.length-1);
		$("#siva-map").append('<div data-rotation="' + rotation.toString() + '" data-vars="' + varsList + '" id="' + name.toString() + '" class="createdDiv ' + cssColor + '" style="position: absolute; top: ' + y.toString() + 'px; left: ' + x.toString() + 'px; width: ' + width.toString() + 'px; height: ' + height.toString() + 'px; transform: rotate(' + rotation.toString() + 'deg)"><div>');
		if (static==false) {
			$("#" + name.toString()).drags();
		}
	}

	function saveDivMap() {
		outputString='';
		firstLoop = true;
		$('.createdDiv').each( function() {
			vars = $(this).data("vars");
			varArray = vars.split(", ");
			if (firstLoop===true) {
				positionType = $(this).css("position");
				firstLoop = false;
			}
			$(varArray).each( function() {
				thisArray = this.split(": ");
				// just take the 2nd part of the array as we know the output will be 'id,left,top,width,height,rotation,' etc
				outputString += thisArray[1] + ",";
			})
		})
		imageName = $('img').attr('src').replace('./images/', '');
		outputString = outputString.substr(0, outputString.length-1); // remove final ","
		console.log('Complete Output:');
		console.log('Image Name: ' + imageName + ' --- Position Type: ' + positionType + ' --- datastring: ' + outputString)
		$.post( "./endpoints/saveDivMap.php", { imagename: imageName, datastring: outputString, positiontype: positionType }).done( function( data ) {
			alert( data );
		});
	}

	function loadDivMap() {
		imageName = $('img').attr('src').replace('./images/', '');
		$.post( "./endpoints/loadDivMap.php", { imagename: imageName }).done( function( data ) {
			masterArray = data.split("\r\n");
			positionType = masterArray[0];
			divMainArray = masterArray[1].split('|');
			$(divMainArray).each( function() {
				divArray = this.split(',');
				divIDName = divArray[0]; x = divArray[1]; y = divArray[2]; width = divArray[3].replace('px',''); height = divArray[4].replace('px',''); rotation = divArray[5].replace('deg','');
				createDiv(divIDName, x, y, width, height, rotation, true);
			})
			$(".createdDiv").fadeOut(1).fadeIn("slow").fadeOut("slow");
		})
	}

</script>
</html>







<!--
Example of sivaArray;
Array
(
    [1] => Array
        (
            [SIVATerminal] => Green1
            [Terminal 1] => 10-11
            [Terminal 2] => 3-2
            [Terminal 3] => 8-7
        )

    [2] => Array
        (
            [SIVATerminal] => Green2
            [Terminal 1] => 2-9
            [Terminal 2] => 4-3
            [Terminal 3] => 2-11
        )

    [3] => Array
        (
            [SIVATerminal] => Green3
            [Terminal 1] => 2-4
            [Terminal 2] => 12-10
            [Terminal 3] => 8-6
        )

	[n] = > Array
		(
			[SIVATerminal] => sivaTerminal
			[Terminal 1] => terminalOneState
			[Terminal 2] => terminalTwoState
			[Terminal 3] => terminalThreeState
		)
)
//-->