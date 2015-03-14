<?
if (!defined("POUET_API")) exit();

$prod = $_GET["id"] ? PouetProd::Spawn((int)$_GET["id"]) : null;
if($prod)
{
  $a = array(&$prod);
  PouetCollectPlatforms( $a );
  PouetCollectAwards( $a );

  $prod->downloadLinks = SQLLib::selectRows(sprintf_esc("select type, link from downloadlinks where prod = %d order by type",$prod->id));
  
  $screenshot = find_screenshot( $prod->id );
  if ($screenshot)
    $prod->screenshot = POUET_CONTENT_URL . $screenshot;

  global $COMPOTYPES;
  $prod->party_compo_name = $COMPOTYPES[ $prod->party_compo ];
  foreach($prod->placings as &$p)
    $p->compo_name = $COMPOTYPES[ $p->compo ];
    
  unset($prod->views);
  unset($prod->latestip);
  unset($prod->addeduser->lastLogin);
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