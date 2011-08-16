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
 * \package 	PhpVishnu
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 * \brief		PhpVishnu is a tool to permit the multiple-inheritance in PHP.
 */

require_once 'PhpVishnu/Exception.php';

/*!
 * \file Core.php
 * \brief PhpVishnuCore : This file is a part of PhpVishnu.
 *
 * \class		PhpVishnuCore "PhpVishnu/Core.php"
 * \copyright	(C) Copyright 2005-2011 Guillaume Smaha.
 * \license		http://www.gnu.org/licenses/ LGPL3
 */
abstract class PhpVishnuCore
{
	/*!
	 * \brief List of parents for all class
	 */
	static private $_t_extend = array();
	
	/*!
	 * \brief List of boolean defining if the getter/setter is generate automatically
	 */
    static private $_t_generate_gsetter = array();
    
	/*!
	 * \brief List of boolean defining if the class is a singleton
	 */
    static private $_t_singleton = array();
    
	/*!
	 * \brief List of singleton
	 */
    static private $_t_singleton_instance = array();
    
	/*!
	 * \brief List of instances of the class
	 */
    private $_t_extend_instances = array();    
    
	/*!
	 * \brief Child of the class
	 */
    private $_child = null;
	
    /*!
     * \brief Constructor
     * 	The parents' values for their contructor or an array containing the values.\n
     *	These parameters is kind of Mixed or Array of mixed.\n\n
     * 	Using example for a class A having 2 parents B and C :\n
     * 		- parent::__construct('value for B from A', null);\n
	 *		- parent::__construct('value for B from A');\n
	 *		- parent::__construct(array('value for B from A'));\n
     * \throws PhpVishnuException
     */
	public function __construct()
	{
		if(isset(self::$_t_extend[get_class($this)]))
		{
			if(count(self::$_t_extend[get_class($this)]) < func_num_args())
				throw new PhpVishnuException('class '.get_class($this).' : There more parameters (number:'.func_num_args().') than parents class ('.count(self::$_t_extend[get_class($this)]).' parents)');
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
				throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is already created !');
			
			self::$_t_singleton_instance[get_called_class()] = $this->childOfChildren();
		}
	}
	
    /*!
     * \brief Magic method to call methods and manage the dynamic getters/setters
     *
     * \param string $methodName Method name
     * \param array $args Array of arguments
     * \throws PhpVishnuException
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
						throw new PhpVishnuException('class '.get_class($this).' : Method "' . $methodName . '" not exists');
						break;
				}
			}
			if($try)
				throw new PhpVishnuException('class '.get_class($this).' : Method "' . $methodName . '" not exists');
		}
        else
        {
			throw new PhpVishnuException('class '.get_class($this).' : Method "' . $methodName . '" not exists');
		}     
	}
	
    /*!
     * \brief Magic method to call static methods
     *
     * \param string $methodName Static method name
     * \param array $args Array of arguments
     * \throws PhpVishnuException
     */
	static public function __callStatic($methodName, $args)
    {
		$className = self::parentObjectContainingMethodStatic($methodName);
		if($className != null)
		{
			return forward_static_call_array(array($className, $methodName), $args);
		}
		else
			throw new PhpVishnuException('class '.get_class($this).' : Static Method "' . $methodName . '" not exists');
	}
	
	
	
    /*!
     * \brief Magic method to call the basic getter
     *
     * \param string $propertyName Property name
     * \throws PhpVishnuException
     */
    public function __get($propertyName)
    {
		$access = $this->getPropertyAccess($propertyName);
		if($access != 'public')
		{
			throw new PhpVishnuException('class '.get_class($this).' : Property '.$propertyName.' is "' . $access . '" in the class '.get_called_class());
		}
		
		return $this->get($propertyName);
	}
	
	
	
    /*!
     * \brief Magic method to call the basic setter
     *
     * \param string $propertyName Property name
     * \param Mixed $value Value to set
     * \throws PhpVishnuException
     */
    public function __set($propertyName, $value)
    {
		$access = $this->getPropertyAccess($propertyName);
		
		if($access == null)
			throw new PhpVishnuException('class '.get_class($this).' : Property '.$propertyName.' doesn\'t exist !');
			
		if($access != 'public')
			throw new PhpVishnuException('class '.get_class($this).' : Property '.$propertyName.' is "' . $access . '" in the class '.get_called_class());
		
		return $this->set($propertyName, $value);
	}
	
    /*!
     * \brief Generic method to get a property value
     *
     * \param string $property Property name
     * \param bool $__noException Define if the value is null then return an exception.
     * \throws PhpVishnuException
     * \return Property value
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
/*
			if($this->$property == null)
			{
				throw new PhpVishnuException('class '.get_class($this).' : Property "' . $property . '" is null or private in '.get_class($this));
			}
			else
			{
				return $this->$property;
			}
*/
			return $this->$property;
		}		
		
		if($value == null && $__noException == false)
			throw new PhpVishnuException('class '.get_class($this).' : Property "' . $property . '" not exists');
			
		return null;
    }

    /*!
     * \brief Generic method to set a property
     *
     * \param string $property Property name
     * \param array $value Value to set
     * \return Property value
     */
    public function set($property, $value)
    {
		$listExclude = array();
		$objectExec = null;
		if (!property_exists($this, $property))
		{			
			$objectExecInit = $this->parentObjectContainingProperty($property, $listExclude);
			$objectExec = $objectExecInit;
			while($objectExec != null)
			{
				$objectExec->$property = $value;
				array_push($listExclude, $objectExec);
				$objectExec = $this->parentObjectContainingProperty($property, $listExclude);
			}
			
			if($objectExecInit != null)
			{
				return $objectExec->$property;
			}
		}
		else
		{
			$objectExec = $this->parentObjectContainingProperty($property, $listExclude);
			while($objectExec != null)
			{
				$objectExec->$property = $value;
				array_push($listExclude, $objectExec);
				$objectExec = $this->parentObjectContainingProperty($property, $listExclude);
			}
			
			$this->$property = $value;
			return $this->$property;
		}		
		
		if($objectExec == null)
			throw new PhpVishnuException('class '.get_class($this).' : Property "' . $property . '" not exists');
    }
	
    /*!
     * \brief Checks if the number of arguments is correct else throw an exception.
     *
     * \param array $args An array containing the arguments
     * \param integer $min The minimum number of arguments
     * \param integer $max The maximum number of arguments
     * \param string $methodName Method name
     * \throws PhpVishnuException
     */
    protected function checkArguments(array $args, $min, $max, $methodName)
    {		
        $argc = count($args);
        if ($argc < $min || $argc > $max)
		{
            throw new PhpVishnuException('class '.get_class($this).' : Method ' . $methodName . ' needs minimally ' . $min . ' and maximally ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }
    
    
    /*!
     * \brief Return the access for a giving property.
     *
     * \param string $propertyName Property name
     * 
     * \return Acces type (public, protected, private)
     */
    protected function getPropertyAccess($propertyName)
    {
		$text = print_r($this, true);
		if(preg_match("#\[($propertyName)(\:(\w+)|\:(\w+)\:(\w+))?\]#", $text, $matches))
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
    
    
    
    /*!
     * \brief Return the object containing the method
     *
     * \param string $methodName Method name
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
	
    
    
    
    /*!
     * \brief Return the object containing the static method
     *
     * \param string $methodName Method name
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
    
    
    /*!
     * \brief Return the object contains the property
     *
     * \param string $propertyName Property name
     * \param array $_excludeObject List of objects to exclude of the search.
     */
    public function parentObjectContainingProperty($propertyName, $_excludeObject = null)
    {
		foreach($this->_t_extend_instances as &$object)
		{
			if($_excludeObject == null || !in_array($object, $_excludeObject))
			{
				if(property_exists($object, $propertyName))
				{
					return $object;
				}
				else
				{
					$obj = &$object->parentObjectContainingProperty($propertyName, $_excludeObject);
					if($obj != null)
						return $obj;
				}			
			}			
		}
		
		return null;
	}
	
	
    /*!
     * \brief Return the child of the children
     *
     * \return The instance of the last child
     */
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


	
    /*!
     * \brief Retrieve singleton instance
     *
     * \return The singleton instance of a class
     */
    public static function getSingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
        if (!isset(self::$_t_singleton_instance[get_called_class()]))
        {
            throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is not created !');
        }
        
        return self::$_t_singleton_instance[get_called_class()]->childOfChildren();
    }
    
    
    /*!
     * \brief Create the singleton instance
     *
     * \return void
     */
    public static function createSingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
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
			throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is already created !');
    }

    /*!
     * \brief Destroy the singleton instance
     *
     * \return void
     */
    public static function destroySingleton()
    {
		if(!isset(self::$_t_singleton[get_called_class()]) || !self::$_t_singleton[get_called_class()])
			throw new PhpVishnuException('class '.get_class($this).' : Singleton::getSingleton() : Singleton "'.get_called_class().'" is not a class singleton !');
			
        unset(self::$_t_singleton_instance[get_called_class()]);
    }

    
    //----------------------------
   
   
    
    /*!
     * \brief Define the list of parents for the class
     *
     * \param ArrayOrString $parentsName Parents name
     */
    static public function defineParentClass($parentsName)
    {
		if(func_num_args() > 1)
		{
			self::$_t_extend[get_called_class()] = func_get_args();
		}
		else if(is_array($array))
		{
			self::$_t_extend[get_called_class()] = $parentsName;
		}
		else
		{
			self::$_t_extend[get_called_class()] = array($parentsName);
		}
	}
    
    /*!
     * \brief Define if the class generate dynamicly the getters/setters or not
     *
     * \param bool $acceptGettersSetters The boolean value
     */
    static public function defineGenerateGetterSetter($acceptGettersSetters)
    {
		self::$_t_generate_gsetter[get_called_class()] = $acceptGettersSetters;
	}
    
    /*!
     * \brief Define if the class is a singleton or not
     *
     * \param bool $isSingleton The boolean value
     */
    static public function defineClassSingleton($isSingleton)
    {
		self::$_t_singleton[get_called_class()] = $isSingleton;
	}
}

?>
