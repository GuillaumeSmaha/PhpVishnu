<?php
/*!
 * \package 	PhpVishnu_Example_Simple
 * \author	2011 Guillaume Smaha.
 * \license	http://www.gnu.org/licenses/ LGPL3
 * \brief	PhpVishnu_Example_Simple is an using example of PhpVishnu.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'PhpVishnu/Exception.php';
require_once 'PhpVishnu/Core.php';

/*!
 * \file B.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		B "B.php"
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
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
