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
 * @author Rob Graham <htmlforms@mellowplace.com>
 * 
 * tests the sfMinaclPropelChoiceValidator
 */
require_once dirname(__FILE__) . '/../bootstrap/unit.php';

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

require_once dirname(__FILE__) . '/../bootstrap/sfMinaclTestErrors.class.php';

$loader = new sfPropelData();
$loader->loadData(sfConfig::get('sf_test_dir').'/fixtures/firm.yml');

$firms = FirmPeer::doSelect(new Criteria());

$t = new lime_test(11);

// test creare on non-existant model throws exception
try
{
	new sfMinaclPropelChoiceValidator('NonExistant');
	$t->fail('Creating for a non-existant model class should have failed');
}
catch(phValidatorException $e)
{
	$t->pass('Creating for a non-existant model class failed');
}

// class to hold the validators errors
$errors = new sfMinaclTestErrors();

// test single choice
$v = new sfMinaclPropelChoiceValidator('Firm');
$v->setMultiple(false);
// test invalid choice
$v->validate('9999', $errors);
$t->is(sizeof($errors), 1, 'Non-existant id returned 1 error');
$t->is($errors[0]->getCode(), sfMinaclPropelChoiceValidator::INVALID, 'Error code was invalid');
$errors->resetErrors();
// test valid choice
$v->validate('1', $errors);
$t->is(sizeof($errors), 0, 'Valid id was ok');
$errors->resetErrors();


// test multiple choice
$v->setMultiple(true);
// test not an array throws exception
try
{
	$v->validate($firms[0]->getId(), $errors);
	$t->fail('Passing a value that isnt an array for multi-choice should error');
	$errors->resetErrors();
}
catch(phValidatorException $e)
{
	$t->pass('Passing a value that isnt an array for multi-choice error\'d');
}
// test invalid choice
$t->is($v->validate(array($firms[0]->getId(), '9999'), $errors), false, 'Non-existant id fails');
$t->is(sizeof($errors), 1, 'Non-existant id returned 1 error');
$t->is($errors[0]->getCode(), sfMinaclPropelChoiceValidator::INVALID, 'Error code was invalid');
$errors->resetErrors();
// test valid choice
$t->ok($v->validate(array($firms[0]->getId()), $errors), 'Valid id validates ok');
$t->is(sizeof($errors), 0, 'Valid id was ok');
$errors->resetErrors();
// test multiple valid
$v->validate(array($firms[0]->getId(), $firms[1]->getId(), $firms[2]->getId()), $errors);
$t->is(sizeof($errors), 0, 'Valid id\'s where ok');
$errors->resetErrors();