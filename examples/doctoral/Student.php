<?php
/*!
 * \package 	PhpVishnu_Example_Doctoral
 * \author	2011 Guillaume Smaha.
 * \license	http://www.gnu.org/licenses/ LGPL3
 * \brief	PhpVishnu_Example_Doctoral is an using example of PhpVishnu.
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
