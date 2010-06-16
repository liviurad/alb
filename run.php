<?php
include_once('config.php');

function execute($cmd) {
  @error_log("INFO: execute $ $cmd");
  @exec($cmd, $output, $ret_var);
  if ($ret_var) {
    @error_log("ERROR: $cmd failed!");
  }
  return array($ret_var, $output);
}

function cmd_trim_cache() {
  $cmd = 'for i in `find cache/ -atime +' . Config::CACHE_LIFETIME . '`; do rm -vf $i; done;';
  list($error, $output) = execute($cmd);
  if ($error) {
    $status = "ERROR";
    $message = "Trim cache failed!";
  }
  else {
    $message = "Cache trimmed - " . count($output) . " files deleted.";
  }
  return "<cmd>" . $_GET['cmd'] . "</cmd>\n" .
    "<status>OK</status>\n" .
    "<message>$message</message>\n" . 
    "<show_message>" . (!$error && count($output) ? 'yes' : 'no') . "</show_message>\n";
}

switch ($_GET['cmd']) {
  case 'trim_cache':
    $result = cmd_trim_cache();
    break;
  default:
    break;
}

header("Content-type: text/xml");
echo "<xml>\n";
echo $result;
echo "</xml>";
?>
