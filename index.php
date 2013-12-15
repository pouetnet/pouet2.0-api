<?
require_once("config.inc.php");

require(POUETAPI_POUET_ROOT_LOCAL . "/bootstrap.inc.php");

function outputJSON( $object )
{
  $out = json_encode( $object, JSON_PRETTY_PRINT );
  if($_GET["callback"])
  {
    $callback = $_GET["callback"];
    $out = $callback . "(" . $out . ")";
  }
  header("Content-type: application/json; charset=utf-8");
  echo $out;
}

$prod = PouetProd::Spawn( $_GET["id"] ? $_GET["id"] : 1 );
$a = array(&$prod);
PouetCollectPlatforms( $a );

outputJSON( $prod );
?>