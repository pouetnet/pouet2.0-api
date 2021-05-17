<?
if (!defined("POUET_API")) exit();

$prod = $_GET["id"] ? PouetProd::Spawn((int)$_GET["id"]) : null;
if(isset($_GET["random"])){
  $prod = null;
  $max_prod = 89000;
  while(!$prod){
    $prod = PouetProd::Spawn(rand(1, $max_prod));
    if(!$prod){ usleep(100000); } // 100ms
  }
}
if($prod)
{
  $a = array(&$prod);
  PouetCollectPlatforms( $a );
  PouetCollectAwards( $a );

  $prod->downloadLinks = SQLLib::selectRows(sprintf_esc("select type, link from downloadlinks where prod = %d order by type",$prod->id));
  
  $s = new BM_Query("credits");
  $s->AddField("credits.role");
  $s->AddWhere(sprintf("credits.prodID = %d",$prod->id));
  $s->Attach(array("credits"=>"userID"),array("users as user"=>"id"));
  $s->AddOrder("credits.role");
  $prod->credits = $s->perform();
}

$result = new stdClass();
if ($prod)
{
  $result->success = true;
  $result->prod = $prod->ToAPI();
}
else
{
  $result->error = true;
}
output($result);
?>
