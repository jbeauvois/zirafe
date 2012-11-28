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

// don't forget the ending '/'
$cfg['web_root'] = 'http://localhost/';
$cfg['var_root'] = '/var/zirafe/'; // mkdir -p /var/zirafe/{files,links,trash} && chown -R www-data /var/zirafe/
$cfg['lang'] = 'fr_FR.UTF-8';
$cfg['style'] = 'default';
$cfg['rewrite'] = true;
$cfg['password'] = '';
$cfg['retention'] =	array(
			'42minutes' => 2520,
			'42hours' => 151200,
			'42days' => 3628800,
			'42weeks' => 25401600,
			);
$cfg['default_retention'] = '42hours';
