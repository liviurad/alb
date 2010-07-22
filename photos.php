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
include_once("config.php");

class Photos {

  protected $album;

  static private $instance = NULL;

  static function getInstance($album = '') {
    if (self::$instance === NULL) self::$instance = new self($album);
    return self::$instance;
  }

  static function isNormalNode($node) { return ($node != '.' && $node != '..'); }

  static function isPhoto($node) { 
    return (
      $node != Config::FOLDER_THUMB_FILENAME && 
      preg_match("/(jpg)$/i", $node)
    );
  }

  function __construct($album = '') { $this->setAlbum($album); }

  function setAlbum($album = '') { 
    if (preg_match("/\.\./", $album)) $album = '';
    $this->album = $album; 
  }

  function getAlbum() { return $this->album; }

  function getAlbumPath($album = NULL) {
    if ($album === NULL) $album = $this->getAlbum();

    return Config::PHOTOS_FOLDER . ($album ? $album . '/' : ''); 
  }

  function getNodePath($node) { return $this->getAlbumPath() . $node; }

  function getPhotoInfo($path) {
    $exposure = $fnumber = $iso = $focal_length = '';
    $exif_data = exif_read_data($path);
    $exposure = isset($exif_data['ExposureTime']) ? $exif_data['ExposureTime'] : '';
    if (isset($exif_data['FNumber'])) {
      $value = $exif_data['FNumber'];
      list($a, $b) = split("/", $value);
      $fnumber = "F" . round($a / $b, 1);
    }
    $iso = isset($exif_data['ISOSpeedRatings']) ? "ISO" . $exif_data['ISOSpeedRatings'] : '';
    if (isset($exif_data['FocalLength'])) {
      $value = $exif_data['FocalLength'];
      list($a, $b) = split("/", $value);
      $focal_length = ($a / $b) . "mm";
    }
    return "$focal_length $exposure $fnumber $iso";
  }

  function getRandomPhoto($album) {
    $ls = array_values(array_filter(scandir($this->getAlbumPath($album)), array("Photos", "isPhoto")));
    if (!count($ls)) return NULL;
    $ra = rand(0, count($ls) - 1);
    $thumb = $ls[$ra];
    return $thumb;
  }

  function get() {
    $ls = array_values(array_filter(scandir($this->getAlbumPath()), array("Photos", "isNormalNode")));
    $photos = array();
    foreach ($ls as $node) {
      $node_path = $this->getNodePath($node);
      if (is_dir($node_path)) {
        if (file_exists($node_path . '/' . Config::FOLDER_THUMB_FILENAME)) {
          $thumb = $node_path . '/' . Config::FOLDER_THUMB_FILENAME;
        } 
        else {
          $thumb = $this->getRandomPhoto(($this->album ? $this->album . '/' : '') . $node);
          if (!$thumb) {
            $thumb = Config::EMPTY_ALBUM_IMG;
          }
          else {
            $thumb = $node_path . '/' . $thumb;
          }
        }

        $photos[] = array(
          "type"       => "album",
          "album"      => $this->album,
          "name"       => $node,
          "path"       => $node_path,
          "info"       => null,
          "thumb"      => $thumb,
        );
      }
      elseif (self::isPhoto($node)) {
        $photos[] = array(
          "type"       => "photo",
          "album"      => $this->album,
          "name"       => $node,
          "path"       => $node_path,
          "info"       => $this->getPhotoInfo($node_path),
          "thumb"      => $node_path,
        );
      }
    }
    return $photos;
  }
}
?>
