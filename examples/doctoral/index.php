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
 * \package 	PhpVishnu_Example_Simple
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu_Example_Simple is an using example of PhpVishnu.
 */

/*!
 * \file index.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 */

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'Student.php';
require_once 'Professor.php';
require_once 'Doctoral.php';

$student = new Student('A student');
$professor = new Professor('A professor');
$doctoral = new Doctoral('A doctoral');


$student->learn();

$professor->teach();

$doctoral->learn();
$doctoral->teach();

echo "\n<br/><br/>\n";
print_r($student);

echo "\n<br/><br/>\n";
print_r($professor);

echo "\n<br/><br/>\n";
print_r($doctoral);
