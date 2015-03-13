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
 * \file C.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \class		C "C.php"
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 */
class C extends PhpVishnuCore
{
	private $_c_private = 'private class C';
	
	protected $_c_protected = 'protected class C';

	public $_c_public = 'public class C';
}



?>
