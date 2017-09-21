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
include_once("strings.php");

class Display
{

    protected $photos;
    protected $template;
    protected $template_filename;
    protected $template_compiled;
    protected $text;

    function __construct($photos = array(), $template = Config::TEMPLATE)
    {
        $this->setPhotos($photos);
        $this->setTemplate($template);
        $this->text = Strings::getInstance("en");
    }

    function setTemplate($template = Config::TEMPLATE)
    {
        $this->template = $template;
        $this->template_filename = Config::ROOT_FOLDER . "templates/" . $this->template . ".html";
    }

    function getTemplate()
    {
        return $this->template;
    }

    function getTemplateFilename()
    {
        return $this->template_filename;
    }

    function setTemplateCompiled($t)
    {
        $this->template_compiled = $t;
    }

    function getTemplateCompiled()
    {
        return $this->template_compiled;
    }

    function setPhotos($photos = array())
    {
        $this->photos = $photos;
    }

    function getPhotos()
    {
        return $this->photos;
    }

    protected function getPageNav()
    {
        $r = '';
        return $r;
    }

    protected function getBreadcrumbNav()
    {
        $photos_obj = Photos::getInstance();
        $parents = explode('/', $photos_obj->getAlbum());
        $links = array();
        $links[] = '<a href="?album=">' . urlencode($this->text->home()) . '</a>';
        $prev = array();
        $cur_album = array_pop($parents);
        foreach ($parents as $parent) {
            if (!$parent) continue;
            $prev[] = $parent;
            $links[] = '<a href="?album=' . urlencode(implode('/', $prev)) . '">' . htmlentities($parent) . '</a>';
        }
        if ($cur_album) $links[] = '<span class="current_album">' . htmlentities($cur_album) . '</span>';
        $r = implode(' ' . Config::BT_NAV_DELIM . ' ', $links);

        return $r;
    }

    protected function getThumbnails()
    {
        $r = '';
        $photos = $this->getPhotos();
        for ($i = 0; $i < Config::THUMBS_PER_PAGE; $i++) {
            if ($i >= count($photos)) break;
            $album = $photos[$i]['album'];
            $path = $photos[$i]['path'];
            $name = $photos[$i]['name'];
            $thumb = $photos[$i]['thumb'];
            $info = $photos[$i]['info'];
            $img_thumb = sprintf('<img src="showphoto.php?photo=%s&size=thumb" alt="' . htmlentities($photos[$i]['type']) . '">', urlencode($thumb));
            if ($photos[$i]['type'] == 'album') {
                $target_album = ($album ? $album . '/' : '') . $name;
                if ($thumb == Config::FOLDER_THUMB_DEFAULT || $thumb == Config::EMPTY_ALBUM_IMG) {
                    $img_thumb = sprintf('<img src="%s" alt="' . htmlentities($photos[$i]['type']) . '">', $thumb);
                }
                $r .= '<div><a href="?album=' . urlencode($target_album) . '">' .
                    '<span class="album_title">' . htmlentities($name) . '</span>' .
                    $img_thumb . '</a></div>' . "\n";
            } elseif ($photos[$i]['type'] == 'photo') {
                $r .= '<div><a href="showphoto.php?photo=' .
                    urlencode($path) . '" rel="lightbox-sal" title="' .
                    htmlentities($info) . '"><span></span>' .
                    $img_thumb .
                    '</a></div>' . "\n";
            }
        }

        return $r;
    }

    protected function compileTemplate()
    {
        $template_string = file_get_contents($this->getTemplateFilename());
        if ($template_string === false) {
            # TODO: handle error
            @error_log("ERROR: something went wrong getting the template file!");
        }

        $placeholders = array(
            "/<% title %>/",
            "/<% stylesheet %>/",
            "/<% root_folder %>/",
            "/<% gallery_desc %>/",
            "/<% breadcrumb_navigation %>/",
            "/<% thumbnails %>/",
            "/<% page_navigation %>/",
            "/<% footer_text %>/",
        );

        $replacements = array(
            Config::TITLE,
            Config::STYLESHEET,
            Config::ROOT_FOLDER,
            Config::DESC,
            $this->getBreadcrumbNav(),
            $this->getThumbnails(),
            $this->getPageNav(),
            Config::FOOTER_TEXT,
        );

        $compiled_template = preg_replace($placeholders, $replacements, $template_string);
        $this->setTemplateCompiled($compiled_template);

    }

    function on()
    {
        # SEEME don't store compiled template in memory, just compile it when requested and return it
        $this->compileTemplate();
        print $this->getTemplateCompiled();
    }
}

