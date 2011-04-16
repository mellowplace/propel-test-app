<?php
/*
 * Firm form template
 */
?>
<dl>
	<dt><label for="<?php echo $this->id('name') ?>">Name</label></dt>
	<dd>
		<input type="text" 
			id="<?php echo $this->id('name') ?>"
			name="<?php echo $this->name('name') ?>" 
			value=""
			maxlength="50"
		/>
	</dd>
</dl>
<input 	type="hidden" 
		id="<?php echo $this->id('id') ?>"
		name="<?php echo $this->name('id') ?>" />
