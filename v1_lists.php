<?php
if (!defined("POUET_API")) exit();

$result = new stdClass();

$s = new BM_query("lists");
$s->AddField("lists.id");
$s->AddField("lists.name");
$s->AddField("lists.desc");
$s->AddField("lists.addedDate");
$s->Attach(array("lists"=>"addedUser"),array("users as addedUser"=>"id"));
$s->Attach(array("lists"=>"owner"),array("users as owner"=>"id"));
$s->AddWhere(sprintf_esc("lists.id=%d",$_GET["id"]));
list($list) = $s->perform();

if ($list)
{
  $result->success = true;
  $result->list = $list;
  
  $s = new BM_query("list_items");
  $s->Attach(array("list_items"=>"itemid"),array("prods as prod"=>"id"));
  $s->AddWhere(sprintf_esc("list_items.list=%d",$list->id));
  $s->AddWhere("list_items.type='prod'");
  $result->list->prods = $s->perform();
  
  $a = array();
  foreach($result->list->prods as $p) $a[] = &$p->prod;
  PouetCollectPlatforms($a);
  
  $s = new BM_query("list_items");
  $s->Attach(array("list_items"=>"itemid"),array("groups as group"=>"id"));
  $s->AddWhere(sprintf_esc("list_items.list=%d",$list->id));
  $s->AddWhere("list_items.type='group'");
  $result->list->groups = $s->perform();
  
  $s = new BM_query("list_items");
  $s->Attach(array("list_items"=>"itemid"),array("parties as party"=>"id"));
  $s->AddWhere(sprintf_esc("list_items.list=%d",$list->id));
  $s->AddWhere("list_items.type='party'");
  $result->list->parties = $s->perform();
  
  $s = new BM_query("list_items");
  $s->Attach(array("list_items"=>"itemid"),array("users as user"=>"id"));
  $s->AddWhere(sprintf_esc("list_items.list=%d",$list->id));
  $s->AddWhere("list_items.type='user'");
  $result->list->users = $s->perform();
  
  $s = new BM_query("list_maintainers");
  $s->Attach(array("list_maintainers"=>"userID"),array("users as user"=>"id"));
  $s->AddWhere(sprintf_esc("list_maintainers.listID = %d",$list->id));
  $result->list->maintainers = $s->perform();
}
else
{
  $result->error = true;
}
output($result);
?>