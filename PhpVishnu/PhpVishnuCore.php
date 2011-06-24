<?php
/**
 * PhpVishnu Framework
 *
 * LICENSE
 * 
 *  (C) Copyright 2011 Guillaume Smaha
 *  This PhpVishnu/PhpVishnuCore.php file is part of PhpVishnu.
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

require_once 'PhpVishnu/Exception.php';

/**
 * PhpVishnuCore
 *
 * @category   PhpVishnu
 * @package    PhpVishnu_Core
 * @copyright  Copyright (c) 2005-2011 PhpVishnu
 * @license    http://www.gnu.org/licenses/ GPL3
 */
abstract class PhpVishnuCore
{
	/**
	 * List of parents for all class
	 *
	 * @var array
	 */
	static private $_t_extend = array();
	/**
	 * List of boolean defining if the getter/setter is generate automatically
	 *
	 * @var array
	 */
    static private $_t_generate_gsetter = array();
	/**
	 * List of boolean defining if the class is a singleton
	 *
	 * @var array
	 */
    static public $_t_singleton = array();
	/**
	 * List of singleton
	 *
	 * @var array
	 */
    static public $_t_singleton_instance = array();
	/**
	 * List of instances of the class
	 *
	 * @var array
	 */
    private $_t_extend_instances = array();    
	/**
	 * child of the class
	 *
	 * @var array
	 */
    private $_child = null;
	
    /**
     * Constructor
     *
     * @param Integer $id
     * @throws PhpVishnu_Exception
     */
	public function __construct()
	{
		if(isset(self::$_t_extend[get_class($this)]))
		{
			if(count(self::$_t_extend[get_class($this)]) < func_num_args())
				throw new PhpVishnu_Exception('There more parameters (number:'.func_num_args().') than parents class ('.count(self::$_t_extend[get_class($this)]).' parents)');
		}
			
		$args = func_get_args(); // Param PhpVishnu
		
		$iClass = 0;
		if(isset(self::$_t_extend[get_class($this)]))
		{
			foreach(self::$_t_extend[get_class($this)] as $className)
			{
				$object = null;
				if(isset($args[$iClass]) && $args[$iClass] != null)
				{
					$paramClass = '';
					if(is_array($args[$iClass]))
					{
						$argc = count($args[$iClass]);
						extract($args[$iClass], EXTR_PREFIX_ALL, 'arg');
						
						$paramClass = '$arg_0';				
						for($i = 1 ; $i < $argc ; $i++)
						{
							$paramClass .= ', $arg_'.$i;
						}
					}
					else
					{
						$paramClass = '$args[$iClass]';
					}
					eval('$object = new $className('.$paramClass.');');
				}
				else
				{
					$object = new $className();
				}
				
				$object->_child = &$this;
				$this->_t_extend_instances[] = $object;
				
				$iClass++;
			}
		}
		
		if(isset(self::$_t_singleton[get_called_class()]) && self::$_t_singleton[get_called_class()])
		{
			if(isset(self::$_t_singleton_instance[get_called_class()]))
				throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is already created !');
			
			self::$_t_singleton_instance[get_called_class()] = $this->childOfChildren();
		}
	}
	
    /**
     * Magic method to call methods
     *
     * @param string $methodName Method name
     * @param array $args Array of arguments
     * @throws PhpVishnu_Exception
     */
	public function __call($methodName, $args)
    {		
		$try = false;
		if(!method_exists($this, $methodName))
		{
			$try = true;
			$object = $this->parentObjectContainingMethod($methodName);
			
			if($object != null)
				return call_user_func_array(array($object, $methodName), $args);
		}
		
		if(isset(self::$_t_generate_gsetter[get_called_class()]) && self::$_t_generate_gsetter[get_called_class()])
		{
			if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches))
			{
				$property = preg_replace("/[A-Z]/e", "'_'.strtolower('\\0')", ($matches[2].$matches[3]));	
				
				switch($matches[1])
				{
					case 'set' :
						$this->checkArguments($args, 1, 1, $methodName);
						return $this->set($property, $args[0]);
						break;
						
					case 'get' :
						$this->checkArguments($args, 0, 0, $methodName);
						return $this->get($property);
						break;
						
					default :
						throw new PhpVishnu_Exception('Method "' . $methodName . '" not exists');
						break;
				}
			}
			if($try)
				throw new PhpVishnu_Exception('Method "' . $methodName . '" not exists');
		}
        else
        {
			throw new PhpVishnu_Exception('Method "' . $methodName . '" not exists');
		}     
	}
	
    /**
     * Magic method to call static methods
     *
     * @param string $methodName Static method name
     * @param array $args Array of arguments
     * @throws PhpVishnu_Exception
     */
	static public function __callStatic($methodName, $args)
    {
		$className = self::parentObjectContainingMethodStatic($methodName);
		if($className != null)
		{
			return forward_static_call_array(array($className, $methodName), $args);
		}
		else
			throw new PhpVishnu_Exception('Static Method "' . $methodName . '" not exists');
	}
	
	
	
    /**
     * Magic method to call the basic getter
     *
     * @param string $propertyName Property name
     * @throws PhpVishnu_Exception
     */
    public function __get($propertyName)
    {
		$access = $this->getPropertyAccess($propertyName);
		if($access != 'public')
		{
			throw new PhpVishnu_Exception('Property '.$propertyName.' is "' . $access . '" in the class '.get_called_class());
		}
		
		return $this->get($propertyName);
	}
	
	
	
    /**
     * Magic method to call the basic setter
     *
     * @param string $propertyName Property name
     * @throws PhpVishnu_Exception
     */
    public function __set($propertyName, $value)
    {
		$access = $this->getPropertyAccess($propertyName);
		
		if($access == null)
			throw new PhpVishnu_Exception('Property '.$propertyName.' doesn\'t exist !');
			
		if($access != 'public')
			throw new PhpVishnu_Exception('Property '.$propertyName.' is "' . $access . '" in the class '.get_called_class());
		
		return $this->set($propertyName, $value);
	}
	
    /**
     * Generic method to get a property value
     *
     * @param string $property Property name
     * @return Property value
     */
    public function get($property, $__noException = false)
    {
		$value = null;
		if (!property_exists($this, $property))
		{
			foreach($this->_t_extend_instances as &$object)
			{
				$value = $object->get($property, true);
				if($value != null)
				{
					return $value;
				}
			}
		}
		else
		{
			if($this->$property == null)
			{
				throw new PhpVishnu_Exception('Property "' . $property . '" is private in '.get_class($this));
			}
			else
			{
				return $this->$property;
			}
		}		
		
		if($value == null && $__noException == false)
			throw new PhpVishnu_Exception('Property "' . $property . '" not exists');
			
		return null;
    }

    /**
     * Generic method to set a property
     *
     * @param string $property Property name
     * @param array $value Value to set
     * @return Property value
     */
    public function set($property, $value)
    {
		$objectExec = null;
		if (!property_exists($this, $property))
		{
			$objectExec = $this->parentObjectContainingProperty($property);
			if($objectExec != null)
			{
				$objectExec->$property = $value;
				return $objectExec->$property;
			}
		}
		else
		{
			$this->$property = $value;
			return $this->$property;
		}		
		
		if($objectExec == null)
			throw new PhpVishnu_Exception('Property "' . $property . '" not exists');
    }
	
    /**
     * Checks if the number of arguments is correct
     *
     * @param array $args
     * @param integer $min Minimum arguments
     * @param integer $max Maximum arguments
     * @param string $max Arguments name
     * @throws PhpVishnu_Exception
     */
    protected function checkArguments(array $args, $min, $max, $methodName)
    {		
        $argc = count($args);
        if ($argc < $min || $argc > $max)
		{
            throw new PhpVishnu_Exception('Method ' . $methodName . ' needs minimaly ' . $min . ' and maximaly ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }
    
    
    public function getPropertyAccess($propertyName)
    {
		$text = print_r($this, true);
		if(preg_match("#\[($propertyName)(\:(\w+)|\:\w\:(\w+))?\]#", $text, $matches))
		{
			if(count($matches) > 2)
				return $matches[count($matches)-1];
				
			return 'public';
		}
		else
		{
			$object = $this->parentObjectContainingProperty($propertyName);
			if($object != null)
			{
				return $object->getPropertyAccess($propertyName);
			}
		}
		
		return null;
    }
    
    
    
    /**
     * Return the object contains the method
     *
     * @param string $className class name
     * @param string $methodName Method name
     */
    public function parentObjectContainingMethod($methodName)
    {
		foreach($this->_t_extend_instances as &$object)
		{
			if(method_exists($object, $methodName))
			{
				return $object;
			}
			else
			{
				$obj = $object->parentObjectContainingMethod($methodName);
				if($obj != null)
					return $obj;
			}			
		}
		
		return null;
	}
	
    
    
    
    /**
     * Return the object contains the static method
     *
     * @param string $className class name
     * @param string $methodName Method name
     */
    static public function parentObjectContainingMethodStatic($methodName)
    {
		if(isset(self::$_t_extend[get_called_class()]))
		{
			foreach(self::$_t_extend[get_called_class()] as $className)
			{
				if(method_exists($className, $methodName))
				{
					return $className;
				}
				else
				{
					$class = $className::parentObjectContainingMethodStatic($methodName);
					if($class != null)
						return $class;
				}
			}        
		}
		
		return null;
	}
    
    
    /**
     * Return the object contains the property
     *
     * @param string $propertyName Property name
     */
    public function parentObjectContainingProperty($propertyName)
    {
		foreach($this->_t_extend_instances as &$object)
		{
			if(property_exists($object, $propertyName))
			{
				return $object;
			}
			else
			{
				$obj = &$object->parentObjectContainingProperty($propertyName);
				if($obj != null)
					return $obj;
			}			
		}
		
		return null;
	}
	
	
	
	public function childOfChildren()
	{
		$child = $this;
		while(1)
		{
			if($child->_child == null)
				return $child;
				
			$child = $child->_child;
		}
	}


	
    /**
     * Retrieve singleton instance
     *
     * @return self
     */
    public static function getSingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
        if (!isset(self::$_t_singleton_instance[get_called_class()]))
        {
            throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is not created !');
        }
        
        return self::$_t_singleton_instance[get_called_class()]->childOfChildren();
    }
    
    
    /**
     * Create the singleton instance
     *
     * @return void
     */
    public static function createSingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
        if (!isset(self::$_t_singleton_instance[get_called_class()]))
        {
			$className = get_called_class();
			
			$args = func_get_args();
			$argc = func_num_args();
			
			$paramClass = '';
			if($argc > 0)
			{
				extract($args, EXTR_PREFIX_ALL, 'arg');
				
				$paramClass = '$arg_0';				
				for($i = 1 ; $i < $argc ; $i++)
				{
					$paramClass .= ', $arg_'.$i;
				}
			}
			eval('self::$_t_singleton_instance[get_called_class()] = new $className('.$paramClass.');');
        }
        else
			throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is already created !');
    }

    /**
     * Destroy the singleton instance
     *
     * @return void
     */
    public static function destroySingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnu_Exception('Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
        unset(self::$_t_singleton_instance[get_called_class()]);
    }

    
    //----------------------------
   
   
    
    /**
     * Define the list of parents
     *
     * @param array/string $array Parents name
     */
    static public function defineParentClass($array)
    {
		if(func_num_args() > 1)
		{
			self::$_t_extend[get_called_class()] = func_get_args();
		}
		else if(is_array($array))
		{
			self::$_t_extend[get_called_class()] = $array;
		}
		else
		{
			self::$_t_extend[get_called_class()] = array($array);
		}
	}
    
    /**
     * Define the list of parents
     *
     * @param array/string $array Parents name
     */
    static public function defineGenerateGetterSetter($bool)
    {
		self::$_t_generate_gsetter[get_called_class()] = $bool;
	}
    
    /**
     * Define the list of singletons
     *
     * @param array/string $array Parents name
     */
    static public function defineClassSingleton($bool)
    {
		self::$_t_singleton[get_called_class()] = $bool;
	}
}

?>
