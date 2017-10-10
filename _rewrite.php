<?
require_once("config.inc.php");
require(POUETAPI_POUET_ROOT_LOCAL . "/bootstrap.inc.php");

$r = new Rewriter();
$r->addRules(array(
  "^\/+v1\/prod\/?$" => "v1_prod.php",
  "^\/+v1\/search\/prod\/?$" => "v1_search_prod.php",

  "^\/+v1\/group\/?$" => "v1_group.php",

  "^\/+v1\/party\/?$" => "v1_party.php",

  "^\/+v1\/stats\/?$" => "v1_stats.php",

  "^\/+adhoc\/prods-from-year\/?$" => "adhoc_prods_year.php",
));
$r->addBootstrap("./functions.inc.php");
$r->addEntryPoint("POUET_API");
$r->rewrite();
?>