<?php
/***************************************************************************
*	Filename	: uninstall.php
*	desc.		: Page de d�sintallation du module "Production"
*	Authors	: Shad
*	created	: 25/05/2011
*	modified	: 25/05/2011
*	version	: 0.1
***************************************************************************/
if (!defined('IN_SPYOGAME')) die("Hacking attempt");

$mod_uninstall_name = "production";
uninstall_mod($mod_unistall_name,$mod_uninstall_table);
?>