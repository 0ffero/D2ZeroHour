<?php
function split_nth($str, $delim, $n)
{
  return array_map(function($p) use ($delim) {
      return implode($delim, $p);
  }, array_chunk(explode($delim, $str), $n));
}


$imageMapName = $_POST["imagename"];
$fileName = "../images/" . $imageMapName . ".map";
if (file_exists($fileName)) {
    $fileContents = file_get_contents($fileName);
    $divArray = split_nth($fileContents, ",", 6);
    $divString = implode('|', $divArray);
    echo $divString;
} else {
    echo 'ERROR: Image Map (' . $fileName . ') doesn\'t exist';
}

?>