<?php
include_once('config.php');

function execute($cmd) {
  @exec($cmd, $output, $ret_var);
  if ($ret_var) {
    @error_log("ERROR: $cmd failed!");
    die("ERROR");
  }
  @error_log("INFO: execute $ $cmd");
  return $output;
}

function cmd_trim_cache() {
  $cmd = 'for i in `find cache/ -atime +' . Config::CACHE_LIFETIME . '`; do rm -vf $i; done;';
  execute($cmd);
  return "OK";
}

$cmd = $_GET['cmd'];

switch ($cmd) {
  case 'trim_cache':
    $result = cmd_trim_cache();
    break;
  default:
    break;
}

echo $result;
?>
