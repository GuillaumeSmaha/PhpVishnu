<?php
/**
 * PhpVishnu Framework
 *
 * LICENSE
 * 
 *  (C) Copyright 2011 Guillaume Smaha
 *  This Core/Exception.php file is part of PhpVishnu.
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
 * @package    PhpVishnu_Core
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ GPL3
 */

/**
 * Exception for Core component.
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Core
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ GPL3
 */
class PhpVishnu_Exception extends Exception
{
    /**
     * @var null|Exception
     */
    private $_previous = null;

    /**
     * Construct the exception
     *
     * @param  string $msg
     * @param  int $code
     * @param  Exception $previous
     * @return void
     */
    public function __construct($msg = '', $code = 0, Exception $previous = null)
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            parent::__construct($msg, (int) $code);
            $this->_previous = $previous;
        } else {
            parent::__construct($msg, (int) $code, $previous);
        }
    }

    /**
     * Overloading
     *
     * For PHP < 5.3.0, provides access to the getPrevious() method.
     * 
     * @param  string $method 
     * @param  array $args 
     * @return mixed
     */
    public function __call($method, array $args)
    {
        if ('getprevious' == strtolower($method)) {
            return $this->_getPrevious();
        }
        return null;
    }

    /**
     * String representation of the exception
     *
     * @return string
     */
    public function __toString()
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            if (null !== ($e = $this->getPrevious())) {
                return $e->__toString() 
                       . "\n\nNext " 
                       . parent::__toString();
            }
        }
        return parent::__toString();
    }

    /**
     * Returns previous Exception
     *
     * @return Exception|null
     */
    protected function _getPrevious()
    {
        return $this->_previous;
    }
}
