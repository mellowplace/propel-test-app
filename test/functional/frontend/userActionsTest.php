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
 * Builds the forms and generates the user actions module
 *
 * @author Rob Graham <htmlforms@mellowplace.com>
 * @package test
 */
require_once dirname(__FILE__) . '/../../bootstrap/functional.php';

$loader = new sfPropelData();
$loader->loadData(sfConfig::get('sf_test_dir').'/fixtures/user.yml');

$browser = new sfTestBrowser();
$t = $browser->test();

/*
 * remove the forms and module generation prior to regenerating
 */
$t->info('Removing and regenerating forms...');
cleanForms();
buildForms();

$t->info('Removing and regenerating the user module...');
cleanModule('frontend', 'user');
generateModule('frontend', 'user', 'User');

/*
 * check the actions, index first...
 */
$browser->get('/user')->
	with('response')->begin()->
		isStatusCode(200)->
		checkElement('table td:contains("Rob Graham")', 1)->
		checkElement('table td:contains("PHP Developer and all round good guy ...")', 1)->
	end()->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'index')->
	end();

$user = UserPeer::getByName('Rob Graham');
/*
 * now try to edit...
 */
$browser->click($user->getId())->
	with('response')->begin()->
		isStatusCode(200)->
		checkElement('label[for="user_name"]', 'Name')->
		checkElement('label[for="user_firm_id_list"]', 'Firm')->
		checkElement('label[for="user_profile"]', 'Profile')->
		checkElement('input[value="Rob Graham"]', 1)->
		checkElement('textarea#user_profile', 'PHP Developer and all round good guy ...')->
		checkElement('select#user_firm_id_list option', 2)->
		checkElement('select#user_firm_id_list option[selected="selected"]', 'My Test Firm')->
		checkElement('select#user_user_interest_list_list option', 3)->
		// only 1 interest should be selected
		checkElement('select#user_user_interest_list_list option[selected="selected"]', 1)->
		checkElement('select#user_user_interest_list_list option[selected="selected"]', 'Cheese')->
	end()->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'edit')->
	end();
/*
 * update the user
 */
$c = new Criteria();
$c->add(FirmPeer::NAME, 'Another Firm');
$newFirm = FirmPeer::doSelectOne($c);
/*
 * get new interests
 */
$cricket = InterestPeer::getByName('Cricket');
$sewing = InterestPeer::getByName('Sewing');

$browser->click('Save', array(
		'user' => array(
			'firm_id' => array('list' => $newFirm->getId()),
			'name' => 'John Smith',
			'profile' => 'Makes beer',
			'user_interest_list' => array('list' => array($cricket->getId(), $sewing->getId()))
		)
	))->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'update')->
	end()->
	followRedirect()-> // to user/edit
	with('response')->begin()->
		isStatusCode(200)->
		/*
		 * check values where updated
		 */
		checkElement('input[value="John Smith"]', 1)->
		checkElement('textarea#user_profile', 'Makes beer')->
		checkElement('select#user_firm_id_list option', 2)->
		checkElement('select#user_firm_id_list option[selected="selected"]', 'Another Firm')->
		// 2 interests should now be selected
		checkElement('select#user_user_interest_list_list option[selected="selected"]', 2)->
	end()->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'edit')->
	end();
	
/*
 * make sure chese isn't one on John's interests
 */
$interests = UserPeer::doSelectOne(new Criteria())->getUserInterests();
$pass = true;
foreach($interests as $i)
{
	if($i->getInterest()->getName()=='Cheese')
	{
		$pass = false;
	}
}
$t->ok($pass, 'Cheese is no longer one of Johns interests');

/*
 * Go back to the list and create a new user
 */
$browser->click('Back to list')->click('New')->
	setField('user[firm_id]', $newFirm->getId())->
	setField('user[name]', 'New User')->
	setField('user[profile]', 'User profile')->
	// deliberately no interests
	click('Save')->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'create')->
	end()->
	followRedirect()-> // to user/edit
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'edit')->
	end()->
	with('response')->begin()->
		isStatusCode(200)->
		checkElement('input[value="New User"]', 1)->
		checkElement('textarea#user_profile', 'User profile')->
		checkElement('select#user_firm_id_list option', 2)->
		checkElement('select#user_firm_id_list option[selected="selected"]', 'Another Firm')->
		// 0 interests should be selected
		checkElement('select#user_user_interest_list_list option[selected="selected"]', 0)->
	end();