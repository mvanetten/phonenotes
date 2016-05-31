<?php


/**
 * phone_notes_get_note
 * Get a note from the database by id
 *
 * @param integer
 * 
 * @return array
 */ 
function phone_notes_get_note($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'phone_notes';
	$current_user = wp_get_current_user();
	$dbnote = $wpdb->get_results($wpdb->prepare("SELECT id,name,number,date,notes,user_id FROM $table_name WHERE id = %d LIMIT 1",$id) );
	if (!is_null($dbnote))
	{
		if ($dbnote[0]->user_id == $current_user->ID || current_user_can('administrator') || current_user_can('super admin'))
		{	
			$Note = new Note($dbnote[0]->id,$dbnote[0]->name,$dbnote[0]->number,$dbnote[0]->date,$dbnote[0]->notes,$dbnote[0]->user_id);
			return $Note;
		}
	}
	return null;
}

/**
 * phone_notes_put_note
 * Saves a note to the database
 *
 * @param name,number,date,notes,id 
 * 
 * @return bool
 */ 
function phone_notes_put_note(Note $note)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'phone_notes';
	$current_user = wp_get_current_user();
	date_default_timezone_set('Europe/Amsterdam');
	
	if (is_null($note->getId()) || $note->getId() == 0)
	{
		//Add new item to database
		return $wpdb->insert( $table_name, array(
				'name' => $note->getName(),
				'number' => $note->getNumber(),
				'date' => $note->getDateTime(),
				'notes' => $note->getNotes(),
				'user_id' => $current_user->ID,
				'update_timestamp' => date('Y-m-d H:i:s')
				));
	}	
	elseif ($note->getId() > 0)
	{
		$id = $note->getId();
		$dbnote = $wpdb->get_results($wpdb->prepare("SELECT id,name,number,date,notes,user_id FROM $table_name WHERE id = %d LIMIT 1",$id));
		if (!is_null($dbnote))
		{
			if ($dbnote[0]->user_id == $current_user->ID || current_user_can('administrator') || current_user_can('super admin'))
			//Update item in database
					return $wpdb->update( 
						$table_name, 
							array( 
								'name' => $note->getName(),	
								'number' => $note->getNumber(),	 
								'date' => $note->getDateTime(),
								'notes' => $note->getNotes(),
								'update_timestamp' => date('Y-m-d H:i:s')
							), 
							array( 'ID' => $note->getId() ), 
							array( 
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
					);
		}		
	}
	return false;
}

/**
 * phone_notes_delete_note
 * Deletes a note from the datebase
 *
 * @param integer
 * 
 * @return bool
 */ 
function phone_notes_delete_note($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'phone_notes';
	$current_user = wp_get_current_user();
	date_default_timezone_set('Europe/Amsterdam');
	$dbnote = $wpdb->get_results($wpdb->prepare("SELECT id,name,number,date,notes,user_id FROM $table_name WHERE id = %d LIMIT 1",$id));
	if (!is_null($dbnote))
	{
		if ($dbnote[0]->user_id == $current_user->ID || current_user_can('administrator') || current_user_can('super admin'))
		{
			return $wpdb->delete( $table_name, array( 'id' => $id ) );
		}
	}
	return false;
}

/**
 * phone_notes_get_notes
 * Gets a list of notes
 *
 * @param void
 * 
 * @return array
 */ 
function phone_notes_get_notes()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'phone_notes';
	$current_user = wp_get_current_user();
	$results;
	$Notes = array();
	
	if (current_user_can('editor'))
	{
		$results = $wpdb->get_results( $wpdb->prepare("SELECT id,name,number,date,notes FROM $table_name WHERE user_id = %d ORDER BY date desc",$current_user->ID) );
	}
	if (current_user_can('administrator') || current_user_can('super admin'))
	{
		$results = $wpdb->get_results( $wpdb->prepare("SELECT id,name,number,date,notes FROM $table_name ORDER BY date desc","") );
	}
	if (count($results > 0))
	{
		foreach ($results as $note)
		{
			//Model Note Construct ($id = null,$name,$number,$datetime,$notes)
			$n = new Note($note->id,$note->name,$note->number,$note->date,$note->notes);
			array_push($Notes, $n);
		}
		return $Notes;
	}
	return null;
}


?>