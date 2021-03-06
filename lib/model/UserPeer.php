<?php


/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Sun Apr 10 21:25:23 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class UserPeer extends BaseUserPeer 
{
	/**
	 * Gets a user from their name
	 * 
	 * @param string $name
	 * @return User
	 */
	public static function getByName($name)
	{
		$c = new Criteria();
		$c->add(self::NAME, $name);
		
		return self::doSelectOne($c);
	}
} // UserPeer
