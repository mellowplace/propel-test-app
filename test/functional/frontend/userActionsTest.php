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
		checkElement('table td:contains("Rob Graham")', 1)->
		checkElement('table td:contains("PHP Developer and all round good guy ...")', 1)->
	end()->
	with('request')->begin()->
		isParameter('module', 'user')->
		isParameter('action', 'edit')->
	end();
