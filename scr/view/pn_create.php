<?php 

function phone_notes_create_page()
{
	date_default_timezone_set("Europe/Amsterdam");
	
	?>
	<h1 id="add-new-user">Create note</h1>
	<p>Create a new phone note</p>
	<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" class="validate">
	<input name="action" type="hidden" value="phone_notes_put_note" />
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row"><label for="pn_name">Name of caller <span class="description">(required)</span></label></th>
			<td><input name="pn_name" type="text" id="pn_name" autofocus value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" required/></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><label for="pn_number">Telephone number <span class="description">(required)</span></label></th>
			<td><input name="pn_number" type="text" pattern="(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)" id="pn_number" value="" maxlength="20" required/></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="pn_date">Date (required)</label></th>
			<td><input name="pn_date" type="text" id="pn_date" value="<?php echo date('Y-m-d H:i') ?>" required /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="pn_notes">Notes (required)</label></th>
			<td><textarea rows="10" cols="35" name="pn_notes" type="text" id="pn_notes" value="" maxlength="1024" required/></textarea></td>
		</tr>
		</table>

		<?php submit_button( 'Add Note'); ?>
	</form>
	
	<?php
	
}
	
?>