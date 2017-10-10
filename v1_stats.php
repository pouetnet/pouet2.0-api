<?
if (!defined("POUET_API")) exit();

$group = $_GET["id"] ? PouetGroup::Spawn((int)$_GET["id"]) : null;
if ($group)
{
  $s = new BM_Query("prods");
  $s->AddWhere(sprintf_esc("(prods.group1 = %d) or (prods.group2 = %d) or (prods.group3 = %d)",$group->id,$group->id,$group->id));
  $prods = $s->perform();
  PouetCollectPlatforms($prods);
  PouetCollectAwards($prods);
}

$result = new stdClass();

$data = unserialize( file_get_contents( POUETAPI_POUET_ROOT_LOCAL . "/cache/pouetbox_stats.cache" ) );
if ($data)
{
  $result->success = true;
  $result->stats = $data;
}
else
{
  $result->error = true;
}
output($result);
?>