<?php 

function phone_notes_edit_page(Note $note)
{
?>	
	<h1 id="add-new-user">Edit note</h1>
	<p>Edit a phone note</p>
	<b><?php echo $note->getErrors() ?></b>
	<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" class="validate">
		<input name="action" type="hidden" value="phone_notes_put_note" />
		<input name="pn_id" type="hidden" value="<?php echo $note->getId() ?>" />
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><label for="pn_name">Name of caller <span class="description">(required)</span></label></th>
				<td><input name="pn_name" type="text" id="pn_name" value="<?php echo $note->getName(); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" required /></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="pn_number">Telephone number <span class="description">(required)</span></label></th>
				<td><input name="pn_number" type="text" id="pn_number" pattern="(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)" value="<?php echo $note->getNumber(); ?>" maxlength="20" required/></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="pn_date">Date </label></th>
				<td><input name="pn_date" id="pn_date" value="<?php echo $note->getDateTime(); ?>" required/></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="pn_notes">Notes</label></th>
				<td><textarea rows="10" cols="35" name="pn_notes" type="text" id="pn_notes" value="" required maxlength="1024"/><?php echo $note->getNotes(); ?></textarea></td>
			</tr>
		</table>

	<?php submit_button('Save'); ?>
	</form>
<?php
}
?>