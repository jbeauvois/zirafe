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

// useful constants
if(!defined('NL'))
{
	define('NL', "\n");
}

$cfg['expiration_time'] = array();
foreach ($cfg['expiration_time_config'] as $e)
{
	$cfg['expiration_time'][date_to_human($e)] = date_to_seconds($e);
}

$cfg['default_expiration'] = date_to_human($cfg['default_expiration_time_config']);
