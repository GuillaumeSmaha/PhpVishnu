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
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3, or (at your option)
 *  any later version.
 *
 *  PhpVishnu is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with PhpVishnu; see the file COPYING3.  If not see
 *  <http://www.gnu.org/licenses/>.
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Test
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ GPL3
 */

require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/PhpVishnuCore.php';
require_once 'test/B.php';
require_once 'test/C.php';

/**
 * A
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Test
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ GPL3
 */
class A extends PhpVishnuCore
{
	private $_a_private = 'private class A';
	
	protected $_a_protected = 'protected class A';

	public $_a_public = 'public class A';
	
	
	public function __construct()
	{
		parent::__construct('protected value for B from A', null);
		//parent::__construct('protected value for B from A');
		//parent::__construct(array('protected value for B from A'));
	}
}

A::defineParentClass('B', 'C');

A::defineGenerateGetterSetter(true);

A::defineClassSingleton(true);

?>
