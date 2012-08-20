<?php

/*
 * This file is part of Zirafe.
 *
 * Zirafe is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Zirafe is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Zirafe. If not, see <http://www.gnu.org/licenses/>.
 */

// Zirafe constants
define('ZIRAFE_VERSION', '0.3');

// directories
define('VAR_FILES', $cfg['var_root'] . 'files/');
define('VAR_LINKS', $cfg['var_root'] . 'links/');
define('VAR_TRASH', $cfg['var_root'] . 'trash/');

// i18n

setlocale(LC_ALL, $cfg['lang']);


// useful constants
if(!defined('NL'))
{
	define('NL', "\n");
}

define('ZIRAFE_42MINUTES', 2520);
define('ZIRAFE_42HOURS', 151200); // ZIRAFE_MINUTE * 60 * 24
