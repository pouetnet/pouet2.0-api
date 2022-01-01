<?
if (!defined("POUET_API")) exit();

$prod = null;
if ($_GET["random"])
{
  $query = new BM_Query( "prods" );
  $query->addExtendedFields();
  $query->AddOrder("RAND()");
  $query->SetLimit(1);
  $prods = $query->perform();

  $prod = reset($prods);
}
else
{
  $prod = $_GET["id"] ? PouetProd::Spawn((int)$_GET["id"]) : null;
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