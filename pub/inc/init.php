<?php

/*
 *  This file is part of Zirafe.
 *
 *  Zirafe is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Zirafe is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Zirafe.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!defined("IN_ZIRAFE")) {
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_ZIRAFE is defined.");
}

if(!defined('ZIRAFE_ROOT')) {
	define('ZIRAFE_ROOT', dirname(dirname(__FILE__))."/");
}

require_once ZIRAFE_ROOT."inc/config.php";
require_once ZIRAFE_ROOT."inc/functions.php";
require_once ZIRAFE_ROOT."inc/settings.php";

// etc + check config + check install
