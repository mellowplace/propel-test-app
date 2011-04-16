<?php
/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Rob
 */
abstract class BaseUserForm extends BaseFormMinaclPropel
{
	public function postInitialize()
	{
		parent::postInitialize();
		/*
		 * Validators for the ID column
		 */
		$id1 = new phNumericValidator();
		$id1->min(-2147483648)->max(2147483647);
		$this->id->setValidator($id1);
		/*
		 * Validators for the FIRM_ID column
		 */
		$firm_id1 = new phNumericValidator();
		$firm_id1->min(-2147483648)->max(2147483647);
		$firm_id2 = new phRequiredValidator();
		$firm_id3 = new sfMinaclPropelChoiceValidator();
		$firm_id3->setMultiple(false)->setModel('Firm');
		$this->firm_id->setValidator(new phValidatorLogic($firm_id1))->
			and_($firm_id2)->		
			and_($firm_id3);		
		/*
		 * Validators for the NAME column
		 */
		$name1 = new phStringLengthValidator();
		$name1->max(50);
		$name2 = new phRequiredValidator();
		$this->name->setValidator(new phValidatorLogic($name1))->
			and_($name2);		
		/*
		 * Validators for the PROFILE column
		 */
		$profile1 = new phStringLengthValidator();
		$this->profile->setValidator($profile1);
	}

	public function getModelName()
	{
		return 'User';
	}


}