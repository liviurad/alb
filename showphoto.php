<?php
/**
 *
 * Copyright (C) 2010 Liviu Radulescu
 * liviurad@gmail.com
 *
 * This file is part of Alb.
 *
 * Alb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 *  Alb is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Alb.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
include_once('config.php');
include_once('utils.php');

$st = Utils::stopwatch();

$photo = $_GET['photo'];
if (!file_exists($photo)) {
  die("ERROR: Photo not available.\n");
}

$ext = "";
if (preg_match("/(jpg|jpeg)$/i", $photo)) $ext = "jpg";
elseif (preg_match("/(png)$/i", $photo)) $ext = "png";
elseif (preg_match("/(gif)$/i", $photo)) $ext = "gif";


$imgsize = getimagesize($photo);
@error_log("$photo {$imgsize['mime']} {$imgsize['type']}");
$width = $imgsize[0];
$height = $imgsize[1];

$size = isset($_GET['size']) ? $_GET['size'] : 0;
$sw = isset($_GET['sw']) ? $_GET['sw'] : 0;
$sh = isset($_GET['sh']) ? $_GET['sh'] : 0;
// init values
$new_height = 0;
$squared = false;

// if thumbnail size is requested, set squared thumbs based on configuration
if ($size === 'thumb' && Config::SQUARE_THUMBS) $squared = true;

// if thumbnail size is requested, use configured thumbnail size
if ($size === 'thumb') {
  $new_height = Config::THUMBS_SIZE;
}
// else, if size is passed, use the values from the array with usual dimenstions (heights)
elseif ($size && Config::$DIMS[$size]) {
  $new_height = Config::$DIMS[$size];
}
// else, try to make the best choice from the dimensions to fill the user browser visible area
elseif (!$new_height && !$size && $sh) {
  foreach (Config::$DIMS as $d => $h) {
    if ($h <= ($sh - 120) && $h > $new_height) {
      $new_height = $h;
      $size = $d;
    }
  }
}
// default values in case everything above failed to determine picture height
if (!$new_height) $new_height = Config::$DIMS["S"];

// do not shrink
if ($new_height > $height) {
  $new_height = $height;
  $size = "O"; // original size
}

$xoffset = $yoffset = 0;

if ($squared) {
  $new_width = $new_height;
  if ($width > $height) {
    $xoffset = ceil(($width - $height) / 2);
    $width = $height;
  }
  else {
    $yoffset = ceil(($height - $width) / 2);
    $height = $width;
  }
}
elseif ($new_height) {
  $new_width = round($width * $new_height / $height);
}

$logline = "INFO: $photo ($size, W {$sw}x{$sh}) - resize ({$width}x{$height} => {$new_width}x{$new_height})";

$quality = Config::IMG_QUALITY;

if ($size === 'thumb') {
  $cache_filename = md5('thumb' . $size . $photo . $squared . Config::THUMBS_SIZE);
}
else {
  $cache_filename = md5('photo' . $size . $photo . $quality);
}
$cachedphoto = Config::CACHE_PATH . $cache_filename . '.' . $ext;
if (file_exists($cachedphoto)) {
  $logline .= " - CACHE HIT ($cachedphoto)";
  $photo = $cachedphoto;
}
else {
  if ($size === 'thumb') {
    $cmd = "convert -verbose \"$photo\" -crop {$width}x{$height}+{$xoffset}+{$yoffset} -thumbnail {$new_height}x{$new_height} \"$cachedphoto\"";
  }
  else {
    $cmd = "convert -verbose \"$photo\" -resize {$new_width}x{$new_height} -bordercolor \"#9999A9\" -border 1 -bordercolor black -border 2 -quality $quality \"$cachedphoto\"";
  }
  @exec($cmd, $output, $ret_var);
  if ($ret_var) {
    @error_log("ERROR: Resizing image ($photo) failed. Command was: $cmd");
    die("ERROR: Resizing image ($photo) failed.");
  }
  $logline .= " - CACHE CREATED ($cachedphoto)";
}

if (preg_match("/(jpg|jpeg)$/i", $photo)) header('Content-type: image/jpeg');
elseif (preg_match("/(png)$/i", $photo)) header('Content-type: image/png');
elseif (preg_match("/(gif)$/i", $photo)) header('Content-type: image/gif');
header("Content-Length: " . filesize($cachedphoto));

$fp = fopen($cachedphoto, "rb");
fpassthru($fp);
fclose($fp);

$logline .= " - took " .  Utils::stopwatch($st);
@error_log($logline);

?>
