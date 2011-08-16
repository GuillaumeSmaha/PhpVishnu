<?php
/*!
 * LICENSE
 * 
 *  Copyright (c) 2005-2011 Guillaume Smaha.
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
 * \package 	PhpVishnu_Test
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu_Test is an using example of PhpVishnu.
 */

require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/Core.php';

/*!
 * \file B.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		B "test/B.php"
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
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
