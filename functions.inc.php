<?
function outputJSON( $object )
{
  $out = json_encode( $object, JSON_PRETTY_PRINT );
  if($_GET["callback"])
  {
    $callback = $_GET["callback"];
    $out = $callback . "(" . $out . ")";
    header("Content-type: application/javascript; charset=utf-8");
  }
  else
  {
    header("Content-type: application/json; charset=utf-8");
  }
  echo $out;
}

function outputXML( $object )
{
  header("Content-type: text/xml; charset=utf-8");
  echo $out;
}
function output( $object )
{
  $format = $_GET["format"];
  switch($format)
  {
  // case "xml":
  //    outputXML( $object );
    default:
      outputJSON( $object );
  }
}
?>
