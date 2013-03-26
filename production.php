<?php
/***************************************************************************
*	filename	: production.php
*	package		: Mod Production
*	version		: 1.4a
*	desc.			: Calcul de la production minière
*	Authors		: Kal Nightmare & Scaler - http://ogsteam.fr
*	created		: 10:30 08/11/2007
*	modified	: 10:34 05/09/2009 
***************************************************************************/

error_reporting(E_ALL);


if (!defined('IN_SPYOGAME')) die("Hacking attempt");

require_once("views/page_header.php");
$start = 101;
$nb_planet = $start + find_nb_planete_user() - 1;
$filename = "mod/production/version.txt";
if (file_exists($filename)) $file = file($filename);
$mod_version = trim($file[1]);
$forum_link = "http://ogsteam.fr/";
$creator_name = "<a href=mailto:jojolam44@hotmail.com>Jojo.lam44</a> &copy; 2006<br />";
$modifier_name1 = "<a href=mailto:kalnightmare@free.fr>Kal Nightmare</a> &copy;2006";
$modifier_name2 = "<a href=mailto:gon.freecks@gmail.com>Scaler</a> &copy; 2007";
$modifier_name3 = "<a>Shad</a> &copy; 2011";
$updator_name = "<a>Pitch314</a> &copy; 2013";

// Récupération des chaines de langue
require_once("mod/production/lang/lang_fr.php");
//if (file_exists("mod/production/lang/lang_".$server_config['language'].".php")) require_once("mod/production/lang/lang_".$server_config['language'].".php");
//if (file_exists("mod/production/lang/lang_".$user_data['user_language'].".php")) require("mod/production/lang/lang_".$user_data['user_language'].".php");

// Enregistrement des données
for ($i=$start;$i<=$nb_planet;$i++) {
	if (isset($_POST['planete'.$i])) {
		if ($_POST['planete'.$i] == 1) {
			if (isset($_POST['SS'.$i]) && isset($_POST['M'.$i]) && isset($_POST['C'.$i]) && isset($_POST['D'.$i]) && isset($_POST['SoP'.$i]) && isset($_POST['FR'.$i])) {
				if (is_numeric($_POST['SS'.$i]) && is_numeric($_POST['M'.$i]) && is_numeric($_POST['C'.$i]) && is_numeric($_POST['D'.$i]) && is_numeric($_POST['SoP'.$i]) && is_numeric($_POST['FR'.$i])) {
					$request = "update ".TABLE_USER_BUILDING." set Sat = ".$_POST['SS'.$i];
					$request .= ", M = ".$_POST['M'.$i].", C = ".$_POST['C'.$i].", D = ".$_POST['D'.$i];
					$request .= ", CES = ".$_POST['SoP'.$i].", CEF = ".$_POST['FR'.$i];
					$request .= " where user_id = ".$user_data["user_id"]." and planet_id = ".$i;
					$db->sql_query($request);
				}
			}
			if (isset($_POST['rap_SS'.$i]) && isset($_POST['rap_M'.$i]) && isset($_POST['rap_C'.$i]) && isset($_POST['rap_D'.$i]) && isset($_POST['rap_SoP'.$i]) && isset($_POST['rap_FR'.$i])) {
				if (is_numeric($_POST['rap_SS'.$i]) && is_numeric($_POST['rap_M'.$i]) && is_numeric($_POST['rap_C'.$i]) && is_numeric($_POST['rap_D'.$i]) && is_numeric($_POST['rap_SoP'.$i]) && is_numeric($_POST['rap_FR'.$i])) {
					$request = "update ".TABLE_USER_BUILDING." set Sat_percentage = ".$_POST['rap_SS'.$i];
					$request .= ", M_percentage = ".$_POST['rap_M'.$i].", C_percentage = ".$_POST['rap_C'.$i].", D_percentage = ".$_POST['rap_D'.$i];
					$request .= ", CES_percentage = ".$_POST['rap_SoP'.$i].", CEF_percentage = ".$_POST['rap_FR'.$i];
					$request .= " where user_id = ".$user_data["user_id"]." and planet_id = ".$i;
					$db->sql_query($request);
				}
			}
		}
	}
}

// Récupération des informations sur l'énergie et les officiers
if (isset($_POST['techno_energie'])) {
	if (is_numeric($_POST['techno_energie'])) {
		$request = "update ".TABLE_USER_TECHNOLOGY." set NRJ = ".$_POST['techno_energie']." where user_id = ".$user_data["user_id"];
		$db->sql_query($request);
	}
    if (isset($_POST['techno_plasma'])) {
        if (is_numeric($_POST['techno_plasma'])) {
            $request = "update ".TABLE_USER_TECHNOLOGY." set Plasma = ".$_POST['techno_plasma']." where user_id = ".$user_data["user_id"];
            $db->sql_query($request);
        }
    }
	$ingenieur = $geologue = '0';
	if (isset($_POST['ingenieur']) && $_POST['ingenieur'] == 'on') $ingenieur = '1';
	if (isset($_POST['geologue']) && $_POST['geologue'] == 'on') $geologue = '1';
	$request = "update ".TABLE_USER." set off_ingenieur = '".$ingenieur."'";
	$request .= ", off_geologue = '".$geologue."'";
	$request .= " where user_id = ".$user_data["user_id"];
	$db->sql_query($request);
}

// Récupération des informations sur les mines
$planet = array("planet_id" => "", "M_percentage" => 0, "C_percentage" => 0, "D_percentage" => 0, "CES_percentage" => 100, "CEF_percentage" => 100, "Sat_percentage" => 100, "fields" => 163);
$quet = mysql_query("SELECT planet_id, M_percentage, C_percentage, D_percentage, CES_percentage, CEF_percentage, Sat_percentage, fields FROM ".TABLE_USER_BUILDING." WHERE user_id = ".$user_data["user_id"]." AND planet_id < 199 ORDER BY planet_id");
$user_building = array_fill($start, $nb_planet, $planet);
while ($row = mysql_fetch_assoc($quet)) {
	$arr = $row;
	unset($arr["planet_id"]);
	$user_percentage[$row["planet_id"]] = $arr;
}

// Récupération des informations sur l'énergie et les officiers
$user_empire = user_get_empire();
$user_building = $user_empire["building"];
$bati = array('','M','C','D','SoP','FR','SS');

// Récupération des informations sur les technologies
if ($user_empire["technology"]) $user_technology = $user_empire["technology"];
else $user_technology = '0';

// Récupération des informations sur les officiers
$query = mysql_fetch_assoc(mysql_query("SELECT `off_ingenieur`, `off_geologue` FROM ".TABLE_USER." WHERE `user_id` = ".$user_data["user_id"]));
$ingenieur = $query["off_ingenieur"];
$geologue = $query["off_geologue"];
//Récupération des informatitions sur la techno plasma
$techno_plasma = $user_technology['Plasma'];

// Réparation des informations sur la vitesse univers
//$query = mysql_fetch_assoc(mysql_query("SELECT `config_value` FROM ".TABLE_CONFIG." WHERE config_name = 'speed_uni'"));
// pour les version d'OGSpy jusqu'à 3.04b, par défaut : 1
// pour l'uni 50 français qui est à vitesse *2, il faut donc mettre... 2 !
//if (!$query["config_value"]) $query["config_value"] = 1;
// modif pour 3.0.7 on economise une requete pour piocher dans global
$vitesse = $server_config['speed_uni']
?>
<script src="http://www.ogsteam.besaba.com/js/stat.js" type="text/javascript"> </script>
<SCRIPT LANGUAGE=Javascript SRC="js/ogame_formula.js"></SCRIPT>
<script type="text/javascript">
var nb_planet = <?php echo $nb_planet;?>;
var start = <?php echo $start;?>;
var batimentsOGSpy = new Array();
var ressource = 2;
<?php

for ($i=$start;$i<=$nb_planet;$i++) {
	if ($user_building[$i]['planet_name'] != '') {
		$Planete[$i] = 1;
		echo "batimentsOGSpy[".$i."] = new Array('".
			$user_building[$i]['planet_name']."','".
			$user_building[$i]['M']."','".
			$user_building[$i]['C']."','".
			$user_building[$i]['D']."','".
			$user_building[$i]['CES']."','".
			$user_building[$i]['CEF']."','".
			$user_building[$i]['Sat']."','".
			$user_building[$i]['temperature_max']."','".
			$user_percentage[$i]['M_percentage']."','".
			$user_percentage[$i]['C_percentage']."','".
			$user_percentage[$i]['D_percentage']."','".
			$user_percentage[$i]['CES_percentage']."','".
			$user_percentage[$i]['CEF_percentage']."','".
			$user_percentage[$i]['Sat_percentage']."',1);\n";
	} else {
		$Planete[$i] = 0;
		echo "batimentsOGSpy[".$i."] = new Array('','','','','','','','','','','','','','',0);\n";
	}
}
echo "vitesse = ".$vitesse.";\n";
?>
function chargement () {
<?php
$temp = array('',8,9,10,11,12,13);
for ($i=$start;$i<=$nb_planet;$i++) {
	for ($b=1;$b<=6;$b++) {
		echo "document.getElementById('".$bati[$b].$i."').value = batimentsOGSpy[".$i."][".$b."];\n";
		echo "document.getElementById('rap_".$bati[$b].$i."').value = batimentsOGSpy[".$i."][".$temp[$b]."];\n";
	}
       $j = $i - 100;
	echo "document.getElementById('global".$j."').checked = true;\n";
}
echo "document.getElementById('techno_energie').value = ".$user_technology['NRJ'].";\n";
echo "document.getElementById('techno_plasma').value = ".$user_technology['Plasma'].";\n";
echo "document.getElementById('ingenieur').checked = ";
if ($ingenieur == 1) echo "true;\n";
else echo "false;\n";
echo "document.getElementById('geologue').checked = ";
if ($geologue == 1) echo "true;\n";
else echo "false;\n";
echo "document.getElementById('full_off').checked = ";
echo "false;\n";
// if ($full_off == 1) echo "true;\n";
// else echo "false;\n";
?>
verif_donnee ();
}
function add (bat,plan,inc) {
bati = new Array('','M','C','D','SoP','FR','SS');
if (bat == 7) {
        document.getElementById('techno_energie').value = parseFloat(document.getElementById('techno_energie').value) + inc;
} else {
    if (bat == 8) {
        document.getElementById('techno_plasma').value = parseFloat(document.getElementById('techno_plasma').value) + inc;
    } else {
        document.getElementById(bati[bat] + plan).value = parseFloat(document.getElementById(bati[bat] + plan).value) + inc;
    }
}
verif_donnee ();
}
function selection (sel) {
if (sel == 0) sel = false;
else sel = true;
for (i=1;i<=nb_planet;i++) document.getElementById('global' + i).checked = sel; 
verif_donnee ();
}
function verif_donnee(envoye) {
<?php
// avant modif pour 3.0.7
//global = new Array(0,1,1,1,1,1,1,1,1,1);
echo "global = new Array(0"; 
for ($i=$start;$i<=$nb_planet;$i++){
    echo ",1"; 
    }
echo ");";
?>

<?php
for ($i=$start;$i<=$nb_planet;$i++){
    $j = $i - 100;
	for ($b=1;$b<=5;$b++) echo "if ((isNaN(parseFloat(document.getElementById('".$bati[$b].$i."').value))) || parseFloat(document.getElementById('".$bati[$b].$i."').value) < 0 ) document.getElementById('".$bati[$b].$i."').value = batimentsOGSpy[".$i."][".$b."];\n";
	echo "if (!document.getElementById('global".$j."').checked) global[".$j."] = 0;\n";
}
for ($i=$start;$i<=$nb_planet;$i++){
	echo "if ((isNaN(parseFloat(document.getElementById('".$bati[6].$i."').value))) || parseFloat(document.getElementById('".$bati[6].$i."').value) < 0 ) document.getElementById('".$bati[$b].$i."').value = batimentsOGSpy[".$i."][6];\n";
}
?>
if ((isNaN(parseFloat(document.getElementById('techno_energie').value))) || parseFloat(document.getElementById('techno_energie').value) < 0 ) document.getElementById('techno_energie').value = technologieNRJ;
if ((isNaN(parseFloat(document.getElementById('techno_plasma').value))) || parseFloat(document.getElementById('techno_plasma').value) < 0 ) document.getElementById('techno_plasma').value = technologiePlasma;

ingenieur = 1;
if (document.getElementById('ingenieur').checked) {
    ingenieur = 1.1;
}

geologue = 1;
if (document.getElementById('geologue').checked) {
    geologue = 1.1;
}

full_off = 1;
if (document.getElementById('full_off').checked) {
    full_off = 1.12;
    document.getElementById('geologue').checked=true;
    document.getElementById('ingenieur').checked=true;
}
if (envoye == 1) document.forms.Save.submit();
else recup_donnee ();
}
function recup_donnee () {
donnee = new Array;
<?php
for ($b=1;$b<=6;$b++){
	echo "donnee['".$bati[$b]."'] = new Array;\n";
	echo "donnee['rap_".$bati[$b]."'] = new Array;\n";
	for ($i=$start;$i<=$nb_planet;$i++){
		echo "donnee['".$bati[$b]."'][".$i."] = parseFloat(document.getElementById('".$bati[$b].$i."').value);\n";
		echo "donnee['rap_".$bati[$b]."'][".$i."] = parseFloat(document.getElementById('rap_".$bati[$b].$i."').value);\n";
	}
}
?>
technologieNRJ = parseFloat(document.getElementById('techno_energie').value);
technologiePlasma = parseFloat(document.getElementById('techno_plasma').value);
calcul ();
}
function calcul () {
ratio = new Array();
cases = new Array();


cases_base = new Array(0<?php
for ($i=$start;$i<=$nb_planet;$i++) echo ", ".($user_building[$i]["fields_used"] - $user_building[$i]['M'] - $user_building[$i]['C'] - $user_building[$i]['D'] - $user_building[$i]['CES'] - $user_building[$i]['CEF']);
?>);
energie = new Array();
energie_tot = new Array();
metal_heure = new Array();
cristal_heure = new Array();
deut_heure = new Array();
metal_heure[nb_planet+1] = 0;
cristal_heure[nb_planet+1] = 0;
deut_heure[nb_planet+1] = 0;
if (full_off != 1) {
    geologue = full_off;
    ingenieur = full_off;
}
for (i=start;i<=nb_planet;i++) {
	if (batimentsOGSpy[i][14] == 1) {
		prod_energie = Math.round((Math.round((donnee['rap_SoP'][i]/100) * (Math.floor(20 * donnee['SoP'][i] * Math.pow(1.1, donnee['SoP'][i])))) + Math.round((donnee['rap_FR'][i]/100) * (Math.floor(30 * donnee['FR'][i] * Math.pow(1.05 + 0.01 * technologieNRJ, donnee['FR'][i])))) + Math.round((donnee['rap_SS'][i]/100) * (donnee['SS'][i] * Math.floor((batimentsOGSpy[i][7] * 1 + 140) / 6)))) * ingenieur); 
		cons_energie = Math.ceil((donnee['rap_M'][i]/100)*(Math.ceil(10 * donnee['M'][i] * Math.pow(1.1, donnee['M'][i])))) + Math.ceil((donnee['rap_C'][i]/100)*(Math.ceil(10 * donnee['C'][i] * Math.pow(1.1, donnee['C'][i])))) + Math.ceil((donnee['rap_D'][i]/100)*(Math.ceil(20 * donnee['D'][i] * Math.pow(1.1, donnee['D'][i]))));
	
        if (cons_energie == 0) cons_energie = 1;
		energie[i] = prod_energie - cons_energie;
		energie_tot[i] = prod_energie;
		ratio[i] = Math.floor((prod_energie/cons_energie)*100)/100;
		if (ratio[i] > 1) ratio[i] = 1;
		if (cons_energie == 1) energie[i] = 0;
        
        metal_heure[i] = vitesse * ( 30 + Math.floor(donnee['rap_M'][i]/100 * ratio[i] * 30 * donnee['M'][i] * Math.pow(1.1, donnee['M'][i]) * (geologue + 0.01 * technologiePlasma)));
        cristal_heure[i] = vitesse * Math.floor(15 + donnee['rap_C'][i]/100 * ratio[i] * 20 * donnee['C'][i] * Math.pow(1.1, donnee['C'][i]) * (geologue + 0.0066 * technologiePlasma));
        deut_heure[i] = vitesse * Math.floor(donnee['rap_D'][i]/100 * ratio[i] * 10 * donnee['D'][i] * Math.pow(1.1, donnee['D'][i]) * (1.44 - 0.004 * batimentsOGSpy[i][7]) * geologue - Math.round((donnee['rap_FR'][i]/100) * 10 * donnee['FR'][i] * Math.pow(1.1, donnee['FR'][i])));

    var j = i-100;
    if (global[j] == 1) {
			metal_heure[nb_planet+1] = metal_heure[nb_planet+1] + metal_heure[i];
			cristal_heure[nb_planet+1] = cristal_heure[nb_planet+1] + cristal_heure[i];
			deut_heure[nb_planet+1] = deut_heure[nb_planet+1] + deut_heure[i];
		}
		cases[i] = cases_base[i] + donnee['M'][i] + donnee['C'][i] + donnee['D'][i] + donnee['SoP'][i] + donnee['FR'][i];
	}
}
ecrire();
}
function ecrire() {
<?php
for ($i=$start;$i<=$nb_planet;$i++) {
	if ($Planete[$i] == 1) {
		echo "if (batimentsOGSpy['".$i."'][14] == 1) {\n";
		echo "\tif (ratio[".$i."] == 1) couleur = 'lime';\n\telse couleur = 'red';\n";
		echo "\tif (cases[".$i."] <= ".$user_building[$i]["fields"].") couleur2 = 'lime';\n\telse couleur2 = 'red';\n";
		echo "\tdocument.getElementById('fact".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + ratio[".$i."] + '</font>';\n";
		echo "\tdocument.getElementById('cases".$i."').innerHTML = '<font color=\'' + couleur2 + '\'>' + format(".$user_building[$i]["fields_used"].") + '</font>';\n";
		echo "\tdocument.getElementById('cases_tot".$i."').innerHTML = format(".$user_building[$i]["fields"].");\n";
		echo "\tdocument.getElementById('energie".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(energie[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('energie_tot".$i."').innerHTML = format(energie_tot[".$i."]);\n";
		echo "\tdocument.getElementById('prodh_M".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(metal_heure[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('prodh_C".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(cristal_heure[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('prodh_D".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(deut_heure[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('prodj_M".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(24 * metal_heure[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('prodj_C".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(24 * cristal_heure[".$i."]) + '</font>';\n";
		echo "\tdocument.getElementById('prodj_D".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + format(24 * deut_heure[".$i."]) + '</font>';\n";
		echo "} else document.getElementById('global".$i."').disabled = true;\n";
	}
} ?>
document.getElementById('prodh_mtot').innerHTML = format(metal_heure[nb_planet+1]);
document.getElementById('prodh_ctot').innerHTML = format(cristal_heure[nb_planet+1]);
document.getElementById('prodh_dtot').innerHTML = format(deut_heure[nb_planet+1]);
document.getElementById('prodh_ptot').innerHTML = format(Math.floor((metal_heure[nb_planet+1] + cristal_heure[nb_planet+1] + deut_heure[nb_planet+1]) / 1000));
document.getElementById('prodj_mtot').innerHTML = format(24 * metal_heure[nb_planet+1]);
document.getElementById('prodj_ctot').innerHTML = format(24 * cristal_heure[nb_planet+1]);
document.getElementById('prodj_dtot').innerHTML = format(24 * deut_heure[nb_planet+1]);
document.getElementById('prodj_ptot').innerHTML = format(Math.floor(24 * (metal_heure[nb_planet+1] + cristal_heure[nb_planet+1] + deut_heure[nb_planet+1]) / 1000));
document.getElementById('prods_mtot').innerHTML = format(24 * 7 * metal_heure[nb_planet+1]);
document.getElementById('prods_ctot').innerHTML = format(24 * 7 * cristal_heure[nb_planet+1]);
document.getElementById('prods_dtot').innerHTML = format(24 * 7 * deut_heure[nb_planet+1]);
document.getElementById('prods_ptot').innerHTML = format(Math.floor(24 * 7 * (metal_heure[nb_planet+1] + cristal_heure[nb_planet+1] + deut_heure[nb_planet+1]) / 1000));
func_rapport();
}
function func_rapport(ress) {
if (ress > 0 && ress < 4) ressource = ress;
rapport_arr = new Array('',deut_heure[nb_planet+1],cristal_heure[nb_planet+1],metal_heure[nb_planet+1]);
rapport = rapport_arr[ressource] / ressource;
document.getElementById('rapport_m').innerHTML = Math.round(100 * metal_heure[nb_planet+1] / rapport) / 100;
document.getElementById('rapport_c').innerHTML = Math.round(100 * cristal_heure[nb_planet+1] / rapport) / 100;
document.getElementById('rapport_d').innerHTML = Math.round(100 * deut_heure[nb_planet+1] / rapport) / 100;
}
function format(x) {
var signe = '';
if (x < 0) {
	x = Math.abs(x);
	signe = '-';
}
var str = x.toString(), n = str.length;
if (n < 4) return (signe + x);
else return (signe + ((n % 3) ? str.substr(0, n % 3) + '&nbsp;' : '')) + str.substr(n % 3).match(new RegExp('[0-9]{3}', 'g')).join('&nbsp;');
}
window.onload = function () {Biper(); chargement();}
</script>
<table width="100%">
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>"><?php echo $lang['prod_uction_mod']."&nbsp;<input type='submit' value='".$lang['prod_save']."' onClick='javascript:verif_donnee (1)'>&nbsp;<input type='submit' value='".$lang['prod_reset'];?>' onClick="javascript:chargement ()"></td>
</tr>
<tr>
	<th><a>
<?php
echo $lang['prod_planete']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) {
	$name[$i] = $user_building[$i]["planet_name"];
	if ($name[$i] == "") $name[$i] = "&nbsp;";
	echo "\t<th><a>".$name[$i]."</a></th>\n";
}
?>
</tr>
<tr>
	<th><a>
<?php
echo $lang['prod_coordinates']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) {
	$coordinates = $user_building[$i]["coordinates"];
	if ($coordinates == "") $coordinates = "&nbsp;";
	else $coordinates = "[".$coordinates."]";
	echo "\t<th>".$coordinates."</th>\n";
}
?>
</tr>
<tr>
	<th><a>
<?php
echo $lang['prod_temperature']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) {
	$temperature[$i] = $user_building[$i]["temperature_max"];
	if ($temperature[$i] == "") $temperature[$i] = "&nbsp;";
	echo "\t<th>".$temperature[$i]."</th>\n";
}
?>
</tr>
<tr>
	<th><a>
<?php
echo $lang['prod_fields']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) echo "\t<th><font color='lime'><span id='cases".$i."'>".$user_building[$i]["fields"]."</span></font> / <font color='lime'><span id='cases_tot".$i."'>".$user_building[$i]["fields_used"]."</span></font></th>\n";
?>
</tr>
<tr>
	<th><a>
<?php
echo $lang['prod_energy']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) echo "\t<th><font color='lime'><span id='energie".$i."'></span></font> / <font color='lime'><span id='energie_tot".$i."'></span></font></th>\n";
?>
</tr>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>"><?php echo $lang['prod_buildings'];?></td>
</tr>
<form name="Save" method="post" action="">
<?php
for ($b=1;$b<=5;$b++) {
	echo "<tr><th><a>".$lang['prod_building_'.$bati[$b]]."</a></th>";
	for ($i=$start;$i<=$nb_planet;$i++) {
		echo "\t<th><a style='cursor: pointer;vertical-align: middle;'  onClick='javascript:add (".$b.",".$i.",-1)'>-</a><input type='text' id='".$bati[$b].$i."' name='".$bati[$b].$i."' size='2' maxlength='2' onBlur='javascript:verif_donnee (0)' value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (".$b.",".$i.",1)'>+</a>\n";
		echo "\t\t<select id='rap_".$bati[$b].$i."' name='rap_".$bati[$b].$i."' onChange='javascript:verif_donnee (0)'>";
		for ($j=100;$j>=0;$j=$j-10) echo "<option value='".$j."'>".$j."%</option>";
		echo "</select></th>\n";
	}
	echo "</tr>";
}
echo "<tr><th><a>".$lang['prod_SS']."</a></th>";
	for ($i=$start;$i<=$nb_planet;$i++) {
		echo "<input type='hidden' name='planete".$i."' value='".$Planete[$i]."'>";
		echo "\t<th><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (6,".$i.",-1)'>-</a><input type='text' id='".$bati[6].$i."' name='".$bati[6].$i."' size='2' maxlength='6' onBlur=\"javascript:verif_donnee (0)\" value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (6,".$i.",1)'>+</a>\n";
		echo "\t\t<select id='rap_".$bati[6].$i."' name='rap_".$bati[6].$i."' onChange='javascript:add (6,".$i.",1)'>";
		for ($j=100; $j>=0; $j=$j-10) echo "<option value='".$j."'>".$j."%</option>";
		echo "</select></th>\n";
	}
	echo "</tr>";
?>
<tr>
<td class="c" colspan="<?php echo $nb_planet;?>"><?php echo $lang['prod_tech_off'];?></td>
</tr>
<tr><th><a><?php echo $lang['prod_technology_En'];?></a></th>
	<th><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (7,0,-1)'>-</a><input type='text' id='techno_energie' name='techno_energie' size='2' maxlength='6' onBlur='javascript:verif_donnee (0)' value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (7,0,1)'>+</a></th>
	<th><a><?php echo $lang['prod_technology_Pl'];?></a></th>
	<th><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (8,0,-1)'>-</a><input type='text' id='techno_plasma' name='techno_plasma' size='2' maxlength='6' onBlur='javascript:verif_donnee (0)' value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (8,0,1)'>+</a></th>
    <th colspan="2" onmouseover="Tip('<table width=&quot;200&quot;><tr><td align=&quot;center&quot; class=&quot;c&quot;><?php echo $lang['prod_officer_E'];?></td></tr><tr><th align=&quot;center&quot;><a><?php echo $lang['prod_officer_E_help'];?></a></th></tr></table>')" onmouseout="UnTip()"><label><input type='checkbox' id='ingenieur' name='ingenieur' onClick='javascript:verif_donnee (0)'> <a><?php echo $lang['prod_officer_E'];?></a></label></th>
	<th colspan="2" onmouseover="Tip('<table width=&quot;200&quot;><tr><td align=&quot;center&quot; class=&quot;c&quot;><?php echo $lang['prod_officer_G'];?></td></tr><tr><th align=&quot;center&quot;><a><?php echo $lang['prod_officer_G_help'];?></a></th></tr></table>')" onmouseout="UnTip()"><label><input type='checkbox' id='geologue' name='geologue' onClick='javascript:verif_donnee (0)'> <a><?php echo $lang['prod_officer_G'];?></a></label></th>
    <th colspan="2" onmouseover="Tip('<table width=&quot;200&quot;><tr><td align=&quot;center&quot; class=&quot;c&quot;><?php echo $lang['prod_officer_full'];?></td></tr><tr><th align=&quot;center&quot;><a><?php echo $lang['prod_officer_full_help'];?></a></th></tr></table>')" onmouseout="UnTip()"><label><input type='checkbox' id='full_off' name='fulloff' onClick='javascript:verif_donnee (0)'> <a><?php echo $lang['prod_officer_full'];?></a></label></th>
</tr>
</form>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_prod_hour']."</td>\n</tr>\n<tr>\n<th><a>".$lang['prod_prod_factor']."</a></th>\n";
for ($i=$start;$i<=$nb_planet;$i++) echo "\t<th><span id='fact".$i."'></span></th>\n";
?>
</tr>
<?php
for ($b=1;$b<=3;$b++) {
	echo "<tr><th><a>".$lang['prod_building_'.$bati[$b]]."</a></th>";
	for ($i=$start;$i<=$nb_planet;$i++) echo "\t<th><span id='prodh_".$bati[$b].$i."'></span></th>\n";
	echo "</tr>";
}
?>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_prod_day']."</td>\n</tr>\n";
for ($b=1;$b<=3;$b++) {
	echo'<tr><th><a>'.$lang['prod_building_'.$bati[$b]].'</a></th>';
	for ($i=$start;$i<=$nb_planet;$i++) echo "\t<th><span id='prodj_".$bati[$b].$i."'></span></th>\n";
	echo "</tr>";
}
?>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_total_prod']."</td>\n</tr>\n<tr><th><table width='100%' style='border:none'><tr><th style='border:none'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:selection (0)' title='".$lang['prod_none']."'>-</a></th><th style='border:none'><a>".$lang['prod_account']."</a></th><th style='border:none'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:selection (1)' title='".$lang['prod_all']."'>+</a></th></tr></table></th>\n";
for ($i=101;$i<=$nb_planet;$i++) {
    $j=$i -100;
	echo "\t<th><label><input type='";
	if ($Planete[$i] == 1) echo "checkbox";
	else echo "hidden";
	echo "' id='global$j' name='global$j' onClick='javascript:verif_donnee (0)'>&nbsp;$name[$i]</label></th>\n";
}
?>
</tr>
<tr>
	<th><a><?php echo $lang['prod_hour'];?></a></th>
<th><a><?php echo $lang['prod_M'];?></a></th>
<th><font color='lime'><span id='prodh_mtot'></span></font></th>
<th><a><?php echo $lang['prod_C'];?></a></th>
<th><font color='lime'><span id='prodh_ctot'></span></font></th>
<th><a><?php echo $lang['prod_D'];?></a></th>
<th><font color='lime'><span id='prodh_dtot'></span></font></th>
<th><a><?php echo $lang['prod_points'];?></a></th>
<th><font color='lime'><span id='prodh_ptot'></span></font></th>
<th><a><?php echo $lang['prod_hour'];?></a></th>
</tr>
<tr>
	<th><a><?php echo $lang['prod_day'];?></a></th>
<th><a><?php echo $lang['prod_M'];?></a></th>
<th><font color='lime'><span id='prodj_mtot'></span></font></th>
<th><a><?php echo $lang['prod_C'];?></a></th>
<th><font color='lime'><span id='prodj_ctot'></span></font></th>
<th><a><?php echo $lang['prod_D'];?></a></th>
<th><font color='lime'><span id='prodj_dtot'></span></font></th>
<th><a><?php echo $lang['prod_points'];?></a></th>
<th><font color='lime'><span id='prodj_ptot'></span></font></th>
<th><a><?php echo $lang['prod_day'];?></a></th>
</tr>
<tr>
	<th><a><?php echo $lang['prod_week'];?></a></th>
<th><a><?php echo $lang['prod_M'];?></a></th>
<th><font color='lime'><span id='prods_mtot'></span></font></th>
<th><a><?php echo $lang['prod_C'];?></a></th>
<th><font color='lime'><span id='prods_ctot'></span></font></th>
<th><a><?php echo $lang['prod_D'];?></a></th>
<th><font color='lime'><span id='prods_dtot'></span></font></th>
<th><a><?php echo $lang['prod_points'];?></a></th>
<th><font color='lime'><span id='prods_ptot'></span></font></th>
<th><a><?php echo $lang['prod_week'];?></a></th>
</tr>
<tr>
	<th><a><?php echo $lang['prod_ratio'];?></a></th>
<th onClick="javascript:func_rapport (3)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_M'];?></a></th>
<th onClick="javascript:func_rapport (3)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_m'></span></font></th>
<th onClick="javascript:func_rapport (2)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_C'];?></a></th>
<th onClick="javascript:func_rapport (2)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_c'></span></font></th>
<th onClick="javascript:func_rapport (1)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_D'];?></a></th>
<th onClick="javascript:func_rapport (1)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_d'></span></font></th>
</tr>
</table>
<br/>
<?php
echo "<div align=center><font size='2'>".sprintf($lang['prod_created_by'],$mod_version,$creator_name,$modifier_name1,$modifier_name2,$modifier_name3)."</font><br />".
"<div align=center><font size='2'>".sprintf($lang['prod_updated_by'],$mod_version,$updator_name)."</font><br />".
	"<font size='1'><a href='".$forum_link."' target='_blank'>".$lang['prod_forum']."</a>.</font></div>";
require_once("views/page_tail.php");


?>
