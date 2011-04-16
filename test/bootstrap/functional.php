<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));

/**
 * Runs the minacl:build-forms task
 */
function buildForms()
{
	$dispatcher = sfContext::getInstance()->getEventDispatcher();
	$formatter = new sfAnsiColorFormatter();
	
	$buildModel = new sfPropelBuildModelTask($dispatcher, $formatter);
	$buildModel->run();
	
	$buildForms = new sfMinaclBuildFormsTask($dispatcher, $formatter);
	$buildForms->run();
}

/**
 * Removes all the forms
 */
function cleanForms()
{
	$fileManager = new sfFileSystem(sfContext::getInstance()->getEventDispatcher(), new sfAnsiColorFormatter());
	$fileManager->remove(array_merge(
		glob(sfConfig::get('sf_lib_dir') . '/form/minacl/base/*'),
		glob(sfConfig::get('sf_lib_dir') . '/form/minacl/view/*')
	));
	$fileManager->remove(glob(sfConfig::get('sf_lib_dir') . '/form/minacl/*'));
	$fileManager->remove(glob(sfConfig::get('sf_lib_dir') . '/form/minacl'));
}

/**
 * Generates a module
 */
function generateModule($app, $module, $modelClass)
{
	$dispatcher = sfContext::getInstance()->getEventDispatcher();
	$formatter = new sfAnsiColorFormatter();
	
	$generateModule = new sfMinaclGenerateModuleTask($dispatcher, $formatter);
	$generateModule->run(array(
		'application' => $app,
		'module' => $module,
		'model' => $modelClass
	));
}

/**
 * Removes artifacts associated with generating a module
 */
function cleanModule($app, $module)
{
	$moduleDir = sfConfig::get('sf_root_dir') . '/'.$app.'/modules/'.$module;
	
	$fileManager = new sfFileSystem(sfContext::getInstance()->getEventDispatcher(), new sfAnsiColorFormatter());
	$fileManager->remove(array_merge(
		glob($moduleDir.'/actions/*'),
		glob($moduleDir.'/templates/*'),
		glob($moduleDir.'/config/*')
	));
	$fileManager->remove(glob($moduleDir.'/*'));
	$fileManager->remove(glob($moduleDir));
}