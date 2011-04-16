<?php
/*
 * User form template
 */
?>
<dl>
	<dt><label for="<?php echo $this->id('firm_id.list') ?>">Firm id</label></dt>
	<dd><?php echo $this->form('firm_id') ?></dd>
	<dt><label for="<?php echo $this->id('name') ?>">Name</label></dt>
	<dd>
		<input type="text" 
			id="<?php echo $this->id('name') ?>"
			name="<?php echo $this->name('name') ?>" 
			value=""
			maxlength="50"
		/>
	</dd>
	<dt><label for="<?php echo $this->id('profile') ?>">Profile</label></dt>
	<dd>
		<textarea 
			id="<?php echo $this->id('profile') ?>"
			name="<?php echo $this->name('profile') ?>"></textarea>
	</dd>
</dl>
<input 	type="hidden" 
		id="<?php echo $this->id('id') ?>"
		name="<?php echo $this->name('id') ?>" />
