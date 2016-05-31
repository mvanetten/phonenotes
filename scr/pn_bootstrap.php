<?php

//Lib
require_once(plugin_dir_path( __FILE__ ) . "lib/dompdf/autoload.inc.php");


//Includes
require_once("includes/pn_repository.php");
require_once("includes/pn_pdf.php");
require_once("includes/pn_helpers.php");

//Model
require_once("model/pn_note.php"); 

require_once("view/pn_index.php");
require_once("view/pn_create.php");
require_once("view/pn_edit.php");
require_once("view/pn_delete.php");


?>