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
 * \package 	PhpVishnu_Example_Doctoral
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu_Example_Doctoral is an using example of PhpVishnu.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/Core.php';

/*!
 * \file Student.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		Student "Student.php"
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 */
class Student extends PhpVishnuCore
{
	/*!
	 * \brief The student name
	 */
	protected $_name;
	
	/*!
	 * \brief Constructor
	 * \param string $name The student name
	 */
	function __construct($name)
	{
		$this->_name = $name;
	}
	
	public function work()
	{
		echo $this->_name." works on the PhpVishnu documentation (Student).<br/>\n";
	}

	public function learn()
	{
		echo $this->_name." learns the PhpVishnu documentation.<br/>\n";
	}
}

?>
