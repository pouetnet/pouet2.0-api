<?php
if (!defined("POUET_API")) exit();

$user = $_GET["id"] ? PouetUser::Spawn((int)$_GET["id"]) : null;

$result = new stdClass();
if ($user)
{
  $result->success = true;
  $result->user = $user->ToAPI();
}
else
{
  $result->error = true;
}
output($result);
?>