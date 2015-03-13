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
require_once 'Student.php';
require_once 'Professor.php';

/*!
 * \file Doctoral.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		Doctoral "Doctoral.php"
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
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

	public function search()
	{
		echo $this->_name." searchs on the PhpVishnu documentation.<br/>\n";
	}
}

Doctoral::defineParentClass('Professor', 'Student');

Doctoral::defineGenerateGetterSetter(true);

?>
