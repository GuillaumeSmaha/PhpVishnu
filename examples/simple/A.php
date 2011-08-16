<?php
/*!
 * LICENSE
 * 
 *  Copyright (c) 2011 Guillaume Smaha.
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
 * \package 	PhpVishnu_Example_Simple
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu_Example_Simple is an using example of PhpVishnu.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/Core.php';
require_once 'B.php';
require_once 'C.php';

/*!
 * \file A.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		A "A.php"
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
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
