<?php
/*!
 * \package 	PhpVishnu_Example_Doctoral
 * \author	2011 Guillaume Smaha.
 * \license	http://www.gnu.org/licenses/ LGPL3
 * \brief	PhpVishnu_Example_Doctoral is an using example of PhpVishnu.
 */

/*!
 * \file index.php
 * \brief This file is an example and a part of PhpVishnu.
 *
 * \copyright	(C) Copyright 2011 Guillaume Smaha.
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
$student->work();

echo "<br/>\n";
$professor->teach();
$professor->work();

echo "<br/>\n";
$doctoral->learn();
$doctoral->teach();
$doctoral->work();

echo "<br/>\n";
$doctoral->setName('John');
$doctoral->learn();
$doctoral->teach();

echo "\n<br/><br/>\n";
print_r($student);

echo "\n<br/><br/>\n";
print_r($professor);

echo "\n<br/><br/>\n";
print_r($doctoral);
