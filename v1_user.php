<?php
if (!defined("POUET_API")) exit();

$user = $_GET["id"] ? PouetUser::Spawn((int)$_GET["id"]) : null;

$result = new stdClass();
if ($user)
{
  $result->success = true;
  $result->user = $user->ToAPI();
  
  $s = new BM_Query();
  $s->AddTable("credits");
  $s->AddField("credits.role");
  $s->Attach(array("credits"=>"prodID"), array("prods as prod"=>"id"));
  $s->AddWhere(sprintf("credits.userID = %d",$user->id));
  $s->AddOrder("credits_prod.releaseDate desc");
  $data = $s->perform();

  $prods = array();
  foreach($data as $v) $prods[] = &$v->prod;
  PouetCollectPlatforms($prods);
  PouetCollectAwards($prods);

  if ($prods)
  {
    $result->user["credits"] = array();
    foreach($prods as $prod)
    {
      $result->user["credits"][] = $prod->ToAPI();
    }
  }
}
else
{
  $result->error = true;
}
output($result);
?>