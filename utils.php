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
class Utils {

  static function stopwatch($st = 0) {
    $mt = microtime(true);
    return sprintf("%.7f", $mt - $st);
  }

}
?>
