<?php


function phone_notes_index_page(Array $notes)
{
?>
	<h1 id="add-new-user">Phone Notes <a href="<?php echo admin_url( 'admin.php?page=phone_notes_create_page'); ?>" class="button button-primary"><?php echo esc_html_x( 'Add New Phone Note', 'user' ); ?></a></h1>

	<p>Index of phone notes</p>

	<table class="wp-list-table widefat fixed striped notes">
		<thead>
		<tr>
			<th scope="col" id="name" class="manage-column column-name column-primary"><span>Name</span></th>
			<th scope="col" id="date" class="manage-column column-date"><span>Date</span></th>
			<th scope="col" id="number" class="manage-column column-number"><span>Number</span></th>
			<th scope="col" id="notes" class="manage-column column-notes"><span>Notes</span></th></tr>
		</thead>

		<tbody id="the-list" data-wp-lists="list:note">
			
			<?php
			
			if (count($notes) == 0 || !is_array($notes))
			{
				echo "<tr>";
					echo "<td>Empty list</td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
				echo "</tr>";
			}
			else
			{
				
				foreach ($notes as $note)
				{
					echo "<tr id='".$note->getId()."'>";
						echo "<td class='name column-name column-primary has-row-actions' data-colname='Name'>".$note->getName()."<br><div class='row-actions'><span class='edit'>".actionlink('Edit','phone_notes','action=edit','id='.$note->getId())." | </span><span class='delete'>".actionlink('Delete','phone_notes','action=delete','id='.$note->getId())."</span></div><button type='button' class='toggle-row'><span class='screen-reader-text'>Show more details</span></button></td>";
						echo "<td class='date column-date' data-colname='Date'>".$note->getDateTime()."</td>";
						echo "<td class='number column-number' data-colname='Number'><a href='tel:".$note->getNumber()."'>".$note->getNumber()."</a></td>";
						echo "<td class='notes column-notes' data-colname='Notes'>".$note->getNotes()."</td>";
					echo "</tr>";
				}
			}
			?>	
		</tbody>
	</table>
	<table>
		<tr>
			<td></td>
			<td>
				<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>">
					<input name="action" type="hidden" value="downloadpdf">
					<?php submit_button( 'Download Overview','secondary' ); ?>
				</form>
			</td>
			<td>
				<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>">
					<input name="action" type="hidden" value="sendpdf">
					<?php submit_button( 'Send Overview','secondary' ); ?>
				</form>
			</td>
		<tr>
	</table>
	
<?php
}
?>