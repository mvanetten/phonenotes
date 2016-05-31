<?php 

function phone_notes_delete_page(Note $note)
{

?>
		<h1 id="add-new-user">Delete note?</h1>
		<p>Delete a phone note</p>
		<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" class="validate" novalidate="novalidate">
		<input name="action" type="hidden" value="phone_notes_delete_note" />
		<input name="pn_id" type="hidden" value="<?php echo $note->getId() ?>" />
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><label for="user_login">Name of caller <span class="description"></span></label></th>
				<td><?php echo $note->getName() ?></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="email">Telephone number <span class="description"></span></label></th>
				<td><?php echo $note->getNumber() ?></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="first_name">Date </label></th>
				<td><?php echo $note->getDateTime() ?></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="last_name">Notes</label></th>
				<td><?php echo $note->getNotes() ?></td>
			</tr>
			</table>

		<?php submit_button( 'Yep Delete!', 'delete' ); ?>
		</form>
<?php
}
?>