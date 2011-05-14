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
$browser->click('Save', array(
		'user' => array(
			'firm_id' => array('list' => $newFirm->getId()),
			'name' => 'John Smith',
			'profile' => 'Makes beer'
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
	end()->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'edit')->
	end();