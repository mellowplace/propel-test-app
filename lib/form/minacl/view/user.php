<?php
/*
 * User form template
 */
?>
<tr>
	<th><label for="<?php echo $this->id('firm_id.list') ?>">Firm id</label></th>
	<td><?php echo $this->form('firm_id') ?></td>
</tr>
<tr>
	<th><label for="<?php echo $this->id('name') ?>">Name</label></th>
	<td>
		<input type="text" 
			id="<?php echo $this->id('name') ?>"
			name="<?php echo $this->name('name') ?>" 
			value=""
			maxlength="50"
		/>
	</td>
</tr>
<tr>
	<th><label for="<?php echo $this->id('profile') ?>">Profile</label></th>
	<td>
		<textarea 
			id="<?php echo $this->id('profile') ?>"
			name="<?php echo $this->name('profile') ?>"></textarea>
	</td>
</tr>
<tr style="display: none;">
	<td colspan="2">
		<input 	type="hidden" 
			id="<?php echo $this->id('id') ?>"
			name="<?php echo $this->name('id') ?>" />
	</td>
</tr>
