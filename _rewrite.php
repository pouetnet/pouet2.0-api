<?
require_once("config.inc.php");
require(POUETAPI_POUET_ROOT_LOCAL . "/bootstrap.inc.php");

$r = new Rewriter();
$r->addRules(array(
  "^\/+v1\/prod\/?$" => "v1_prod.php",
));
$r->addBootstrap("./functions.inc.php");
$r->addEntryPoint("POUET_API");
$r->rewrite();
?>