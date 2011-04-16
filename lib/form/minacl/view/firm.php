<?php
/*
 * Firm form template
 */
?>
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
<tr style="display: none;">
	<td colspan="2">
		<input 	type="hidden" 
			id="<?php echo $this->id('id') ?>"
			name="<?php echo $this->name('id') ?>" />
	</td>
</tr>
