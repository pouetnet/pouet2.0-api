<?
if (!defined("POUET_API")) exit();

$prod = $_GET["id"] ? PouetProd::Spawn((int)$_GET["id"]) : null;
if($prod)
{
  $a = array(&$prod);
  PouetCollectPlatforms( $a );
  PouetCollectAwards( $a );
  unset($prod->views);
}

$result = new stdClass();
if ($prod)
{
  $result->success = true;
  $result->prod = $prod;
}
else
{
  $result->error = true;
}
output($result);
?>