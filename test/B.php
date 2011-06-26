<?php
/**
 * PhpVishnu Framework
 *
 * LICENSE
 * 
 *  (C) Copyright 2011 Guillaume Smaha
 *  This A.php file is part of PhpVishnu.
 *
 *  PhpVishnu is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as
 *  published by the Free Software Foundation; either version 3,
 *  or (at your option) any later version.
 *
 *  PhpVishnu is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with PhpVishnu; see the file COPYING3. If not see
 *  <http://www.gnu.org/licenses/>.
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Test
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ LGPL3
 */

require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/PhpVishnuCore.php';

/**
 * B
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Test
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ LGPL3
 */
class B extends PhpVishnuCore
{
	private $_b_private = 'private class B';
	
	protected $_b_protected = 'protected class B';

	public $_b_public = 'public class B';
	
	function __construct($protectedValue)
	{
		$this->_b_protected = $protectedValue;
	}
}

?>
