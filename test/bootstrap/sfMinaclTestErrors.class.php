<?php
/*
 * Minacl Project: An HTML forms library for PHP
 *          https://github.com/mellowplace/PHP-HTML-Driven-Forms/
 * Copyright (c) 2010, 2011 Rob Graham
 *
 * This file is part of Minacl.
 *
 * Minacl is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * Minacl is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with Minacl.  If not, see
 * <http://www.gnu.org/licenses/>.
 */

/**
 * stub class for testing validators
 *
 * @author Rob Graham <htmlforms@mellowplace.com>
 * @package test
 */
class sfMinaclTestErrors implements phValidatable, ArrayAccess, Countable
{
	protected $_errors = array();

	/**
	 * allows a validator to attach an error message to this element
	 * @param phValidatorError $error
	 */
	public function addError(phValidatorError $error)
	{
		$this->_errors[] = $error;
	}

	/**
	 * resets any error messages this element might have
	 */
	public function resetErrors()
	{
		$this->_errors = array();
	}

	/**
	 * gets any error messages that have been added to this element
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * @param offset
	 */
	public function offsetExists ($offset)
	{
		return array_key_exists($offset, $this->_errors);
	}

	/**
	 * @param offset
	 */
	public function offsetGet ($offset)
	{
		return $this->_errors[$offset];
	}

	/**
	 * @param offset
	 * @param value
	 */
	public function offsetSet ($offset, $value)
	{
		$this->_errors[$offset] = $value;
	}

	/**
	 * @param offset
	 */
	public function offsetUnset ($offset)
	{
		unset($this->_errors[$offset]);
	}

	public function count ()
	{
		return sizeof($this->_errors);
	}
	
	public function validate()
	{
		
	}
}