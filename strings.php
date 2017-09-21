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
class Strings
{
  protected  $lang;

  protected $data = array(
    "en" => array(
      "home" => "Home",
      "empty_album" => "No images",
      "page_nav" => "Page %s of %s",
      "loading"  => "Loading...",
    )
  );

  static private $instance = null;

  static function getInstance($lang = "en") {
    if (self::$instance === null) self::$instance = new self($lang);
    self::$instance->setLang($lang);
    return self::$instance;
  }

  function __construct($lang = "en") { $this->setLang($lang); }

  function setLang($lang = "en") { $this->lang = strtolower($lang); }

  function home() { return $this->data[$this->lang]["home"]; }

  function emptyAlbum() { return $this->data[$this->lang]["empty_album"]; }

  function pageNav($current_page, $total_pages) { 
    return sprintf($this->data[$this->lang]["page_nav"], $current_page, $total_pages);
  }

  function loading() { return $this->data[$this->lang]["loading"]; }
}
