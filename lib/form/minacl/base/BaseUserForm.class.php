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
	public function preInitialize()
	{
		parent::preInitialize();
		$firm_id = new sfMinaclPropelChooser('firm_id', 'propelSingleSelect', 'Firm');
		$this->addForm($firm_id);
		$user_interest_list = new sfMinaclPropelChooser('user_interest_list', 'propelMultiSelect', 'Interest');
		$this->addForm($user_interest_list);
	}
	
	public function postInitialize()
	{
		parent::postInitialize();
		/*
		 * Validators for the ID column
		 */
		$id1 = new phNumericValidator();
		$id1->decimal(false)->min(-2147483648)->max(2147483647);
		$this->id->setValidator($id1);
		/*
		 * Validators for the FIRM_ID column
		 */
		$firm_id1 = new phNumericValidator();
		$firm_id1->decimal(false)->min(-2147483648)->max(2147483647);
		$firm_id2 = new phRequiredValidator();
		$firm_id3 = new sfMinaclPropelChoiceValidator('Firm');
		$firm_id3->setMultiple(false);
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
		/*
		 * Validators for the UserInterest many 2 many table
		 */
		$user_interest_list = new sfMinaclPropelChoiceValidator('Interest');
		$user_interest_list->setMultiple(true);
		$this->user_interest_list->setValidator($user_interest_list);
	}

	public function getModelName()
	{
		return 'User';
	}


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if ($this->getView()->hasData('user_interest_list'))
    {
      $values = array();
      foreach ($this->object->getUserInterests() as $obj)
      {
        $values[] = $obj->getInterestId();
      }

      $this->user_interest_list->bind($values);
    }

  }

  protected function saveObject($con)
  {
    $object = parent::saveObject($con);

    $this->saveUserInterestList($con);
    
    return $object;
  }

  public function saveUserInterestList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!$this->getView()->hasData('user_interest_list'))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(UserInterestPeer::USER_ID, $this->object->getPrimaryKey());
    UserInterestPeer::doDelete($c, $con);

    $values = $this->user_interest_list->getValue();
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UserInterest();
        $obj->setUserId($this->object->getPrimaryKey());
        $obj->setInterestId($value);
        $obj->save();
      }
    }
  }

}