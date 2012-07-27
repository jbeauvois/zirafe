<?php
/*
 *  Jyraphe, your web file repository
 *  Copyright (C) 2008  Julien "axolotl" BERNARD <axolotl@magieeternelle.org>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Jyraphe constants

define('JYRAPHE_PACKAGE', 'Jyraphe');
define('JYRAPHE_VERSION', '4.2');

// directories

define('VAR_FILES', $cfg['var_root'] . 'files/');
define('VAR_LINKS', $cfg['var_root'] . 'links/');
define('VAR_TRASH', $cfg['var_root'] . 'trash/');

// i18n

setlocale(LC_ALL, $cfg['lang']);

bindtextdomain(JYRAPHE_PACKAGE, JYRAPHE_ROOT . 'lib/locale');
textdomain(JYRAPHE_PACKAGE);


// useful constants

if(!defined('NL')) {
  define('NL', "\n");
}

define('JYRAPHE_42MINUTES', 2520);
define('JYRAPHE_42HOURS', 151200); // JYRAPHE_MINUTE * 60 * 24

?>
