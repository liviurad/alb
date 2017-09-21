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

class Config
{
    const ROOT_FOLDER = "./";
    const PHOTOS_FOLDER = "photos/";

    // max size of thumbnails
    const THUMBS_SIZE = 120;
    // squared tumbnails
    const SQUARE_THUMBS = true;
    // how many thumbnails to display per page
    const THUMBS_PER_PAGE = 1000; # TODO: implement pagination

    // will use template/{$template}.tpl
    const TEMPLATE = "default";
    // will use css/{$default}.css
    const STYLESHEET = "default";

    // filename to use as album/folder image
    const FOLDER_THUMB_FILENAME = "folder.jpg";
    // default image to use as album/folder image
    const FOLDER_THUMB_DEFAULT = "images/default_folder.png";
    // image to use when an album (folder) is empty
    const EMPTY_ALBUM_IMG = "images/empty_album.png";
    // max album title length
    const ALBUM_NAME_MAX_LEN = 30; # TODO: implement name truncation
    // suffix to add when name is truncated
    const ALBUM_NAME_TRUNC_SUFFIX = '...';

    // gallery title
    const TITLE = "Alb Demo";
    // text displayed right under title
    const DESC = "<a href=\"http://github.com/liviurad/alb\">source code</a>";
    // text displayed at the bottom, html allowed
    const FOOTER_TEXT = "Copyright (C) 2010 Liv Ra";
    # TODO: keywords, SEO

    // language
    const LANG = 'en';

    // cache for resized photos
    const CACHE_PATH = "cache/";
    const CACHE_LIFETIME = "120"; // days

    // images dimensions (heights) to chose from when tries to fit picture to user browser visible area
    static $DIMS = array("T" => 120, "S" => 270, "M" => 400, "L" => 540, "XL" => 680, "X2" => 860, "X3" => 1040);

    const IMG_QUALITY = 85;

    // breadcrumb nav delimiter
    const BT_NAV_DELIM = ">";
}
