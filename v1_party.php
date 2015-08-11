<?
if (!defined("POUET_API")) exit();

$party = PouetParty::spawn($_GET["id"]);
$year = (int)$_GET["year"];

$result = new stdClass();
if ($party)
{
  $result->success = true;
  $result->party = $party;

  $prods = array();
  $s = new BM_Query("prods");
  $s->AddWhere( sprintf_esc("(prods.party = %d AND prods.party_year = %d) or (prodotherparty.party = %d AND prodotherparty.party_year = %d)",$party->id,$year,$party->id,$year) );
  
  // this is where it gets nasty; luckily we can fake it relatively elegantly: ORM won't notice if we override some of the field selections
  $s->AddJoin("left","prodotherparty",sprintf_esc("prodotherparty.prod = prods.id and (prodotherparty.party = %d AND prodotherparty.party_year = %d)",$party->id,$year));
  foreach($s->fields as &$v)
  {
    if ($v == "prods.party_compo as prods_party_compo")
    {
      $v = "COALESCE(prodotherparty.party_compo,prods.party_compo) as prods_party_compo";
    }
    if ($v == "prods.party_place as prods_party_place")
    {
      $v = "COALESCE(prodotherparty.party_place,prods.party_place) as prods_party_place";
    }
  }
  
  $result->prods = $s->perform();
  foreach($result->prods as $prod)
  {
    global $COMPOTYPES;
    $prod->party_compo_name = $COMPOTYPES[ $prod->party_compo ];
    foreach($prod->placings as &$p)
      $p->compo_name = $COMPOTYPES[ $p->compo ];
    unset($prod->views);
    unset($prod->latestip);
    unset($prod->addeduser->lastLogin);
  }  

  $inv = new BM_Query("prods");
  $inv->AddWhere( sprintf_esc("(prods.invitation = %d AND prods.invitationyear = %d)",$party->id,$year,$party->id,$year) );
  $inv->AddOrder( "prods.addedDate" );
  $result->invitations = $inv->perform();

}
else
{
  $result->error = true;
}
output($result);
?>