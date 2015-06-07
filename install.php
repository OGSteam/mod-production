<?php
if (!defined('IN_SPYOGAME')) die("Hacking attempt");
global $db, $server_config;
$is_ok = false;
$mod_folder = "production";
$is_ok = install_mod($mod_folder);
if ($is_ok == true)
	{
		// Vérification de la présence des informations officiers et insertion si besoin (OGSpy < 3.05)
		$quet = $db->sql_query("SELECT * FROM ".TABLE_USER." WHERE `off_amiral` = '0' OR `off_amiral` = '1' LIMIT 1");
		if (!$quet) 
			{
				$query = "ALTER TABLE ".TABLE_USER." ADD COLUMN `off_amiral` ENUM( '0', '1' ) NOT NULL DEFAULT '0',
					ADD COLUMN `off_ingenieur` ENUM( '0', '1' ) NOT NULL DEFAULT '0',
					ADD COLUMN `off_geologue` ENUM( '0', '1' ) NOT NULL DEFAULT '0',
					ADD COLUMN `off_technocrate` ENUM( '0', '1' ) NOT NULL DEFAULT '0'";
				$db->sql_query($query);
			}
	}
else
	{
		echo  "<script>alert('Désolé, un problème a eu lieu pendant l'installation, corrigez les problèmes survenue et réessayez.');</script>";
	}
?>