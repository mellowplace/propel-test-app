<?php


/**
 * Skeleton subclass for performing query and update operations on the 'interest' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Sat May 14 16:42:58 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class InterestPeer extends BaseInterestPeer
{
	/**
	 * @param string $name
	 * @return Interest
	 */
	public static function getByName($name)
	{
		$c = new Criteria();
		$c->add(InterestPeer::NAME, $name);
		return self::doSelectOne($c);
	}
} // InterestPeer
