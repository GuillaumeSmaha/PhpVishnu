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
 * \package 	PhpVishnu_Example_Doctoral
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu_Example_Doctoral is an using example of PhpVishnu.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/Core.php';
require_once 'Student.php';
require_once 'Professor.php';

/*!
 * \file Doctoral.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		Doctoral "Doctoral.php"
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 */
class Doctoral extends PhpVishnuCore
{
	/*!
	 * \brief The professor name
	 */
	protected $_name;
	
	/*!
	 * \brief Constructor
	 * \param string $name The professor name
	 */
	function __construct($name)
	{
		$this->_name = $name;
		parent::__construct($name, $name);
	}


	public function work()
	{
		echo $this->_name." works on the PhpVishnu documentation.<br/>\n";
	}
}

Doctoral::defineParentClass('Professor', 'Student');

Doctoral::defineGenerateGetterSetter(true);

?>
