<?php
/**
 * Firm form base class.
 *
 * @method Firm getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Rob
 */
abstract class BaseFirmForm extends BaseFormMinaclPropel
{
	public function preInitialize()
	{
		parent::preInitialize();
	}
	
	public function postInitialize()
	{
		parent::postInitialize();
		/*
		 * Validators for the ID column
		 */
		if($this->getView()->hasData('id'))
		{
			$id1 = new phNumericValidator();
			$id1->decimal(false)->min(-2147483648)->max(2147483647);
			$this->id->setValidator($id1);
		}
		/*
		 * Validators for the NAME column
		 */
		if($this->getView()->hasData('name'))
		{
			$name1 = new phStringLengthValidator();
			$name1->max(50);
			$this->name->setValidator($name1);
		}
	}

	public function getModelName()
	{
		return 'Firm';
	}


}