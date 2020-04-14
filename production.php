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

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

require_once("views/page_header.php");

$start = 101;
$nb_planete_reel = find_nb_planete_user($user_data["user_id"]);
$nb_planet = $start + $nb_planete_reel - 1;
$filename = "mod/production/version.txt";
if (file_exists($filename)) $file = file($filename);
$mod_version = trim($file[1]);
$forum_link = "http://ogsteam.fr/";
$creator_name = "<a href=mailto:jojolam44@hotmail.com>Jojo.lam44</a> &copy; 2006<br />";
$modifier_name1 = "<a href=mailto:kalnightmare@free.fr>Kal Nightmare</a> &copy;2006";
$modifier_name2 = "<a href=mailto:gon.freecks@gmail.com>Scaler</a> &copy; 2007";
$modifier_name3 = "<a>Shad</a> &copy; 2011";
$updator_name = "<a>Pitch314</a> &copy; 2015";

// Récupération des chaines de langue
require_once("mod/production/lang/lang_fr.php");
//if (file_exists("mod/production/lang/lang_".$server_config['language'].".php")) require_once("mod/production/lang/lang_".$server_config['language'].".php");
//if (file_exists("mod/production/lang/lang_".$user_data['user_language'].".php")) require("mod/production/lang/lang_".$user_data['user_language'].".php");

// Détection de la class
//$class_collect = ($user_data['user_class'] == 'COL') ? '1' : '0';
//echo "<input type='hidden' id='class_collect' value='" . $class_collect . "'/>";

/*** Enregistrement des données  ***/
if(isset($_POST['s_save']) || isset($_POST['techno_energie'])) {
    //Planètes
    for ($i=$start;$i<=$nb_planet;$i++) {
        if (isset($_POST['planete'.$i])) {
            if ($_POST['planete'.$i] == 1) {
                if (isset($_POST['SS'.$i]) && isset($_POST['M'.$i]) && isset($_POST['C'.$i]) && isset($_POST['D'.$i]) && isset($_POST['SoP'.$i]) && isset($_POST['FR'.$i]) && isset($_POST['FO'.$i])) {
                    if (is_numeric($_POST['SS'.$i]) && is_numeric($_POST['M'.$i]) && is_numeric($_POST['C'.$i]) && is_numeric($_POST['D'.$i]) && is_numeric($_POST['SoP'.$i]) && is_numeric($_POST['FR'.$i]) && is_numeric($_POST['FO'.$i])) {
                        $request = "UPDATE ".TABLE_USER_BUILDING." SET Sat = ".$_POST['SS'.$i];
                        $request .= ", M = ".$_POST['M'.$i].", C = ".$_POST['C'.$i].", D = ".$_POST['D'.$i];
                        $request .= ", CES = ".$_POST['SoP'.$i].", CEF = ".$_POST['FR'.$i].", `FOR` = ".$_POST['FO'.$i];
                        $request .= " WHERE user_id = ".$user_data["user_id"]." AND planet_id = ".$i;
                        $db->sql_query($request);
                    }
                }
                if (isset($_POST['rap_SS'.$i]) && isset($_POST['rap_M'.$i]) && isset($_POST['rap_C'.$i]) && isset($_POST['rap_D'.$i]) && isset($_POST['rap_SoP'.$i]) && isset($_POST['rap_FR'.$i]) && isset($_POST['rap_FO'.$i])) {
                    if (is_numeric($_POST['rap_SS'.$i]) && is_numeric($_POST['rap_M'.$i]) && is_numeric($_POST['rap_C'.$i]) && is_numeric($_POST['rap_D'.$i]) && is_numeric($_POST['rap_SoP'.$i]) && is_numeric($_POST['rap_FR'.$i]) && is_numeric($_POST['rap_FO'.$i])) {
                        $request = "UPDATE ".TABLE_USER_BUILDING." SET Sat_percentage = ".$_POST['rap_SS'.$i];
                        $request .= ", M_percentage = ".$_POST['rap_M'.$i].", C_percentage = ".$_POST['rap_C'.$i].", D_percentage = ".$_POST['rap_D'.$i];
                        $request .= ", CES_percentage = ".$_POST['rap_SoP'.$i].", CEF_percentage = ".$_POST['rap_FR'.$i].", FOR_percentage = ".$_POST['rap_FO'.$i];
                        $request .= " WHERE user_id = ".$user_data["user_id"]." AND planet_id = ".$i;
                        $db->sql_query($request);
                    }
                }
            }
        }
    }
    // Technologie
    if(isset($_POST['techno_energie']) && isset($_POST['techno_plasma'])) {
        if(is_numeric($_POST['techno_energie']) && is_numeric($_POST['techno_plasma'])) {
            $request  = "UPDATE ".TABLE_USER_TECHNOLOGY." SET NRJ = ".$_POST['techno_energie'];
            $request .= ", Plasma = ".$_POST['techno_plasma'];
            $request .= " WHERE user_id = ".$user_data["user_id"];
            $db->sql_query($request);
        }
    }
    //officier
    if(isset($_POST['c_off_full']) && $_POST['c_off_full'] == 'on') {
        $request = "UPDATE ".TABLE_USER." SET off_commandant='1', off_amiral='1', off_geologue='1', off_ingenieur='1', off_technocrate='1'";
        $request .= " WHERE user_id = ".$user_data["user_id"];
        //Mise à jour $user_data car non passage par index.php pour renouveler le tampon
        $user_data['off_commandant'] = $user_data['off_amiral']   = 1;
        $user_data['off_ingenieur']  = $user_data['off_geologue'] = 1;
        $user_data['off_technocrate'] = 1;
    	$db->sql_query($request);
    } else {
        $ingenieur = $geologue = 0;
        $user_data['off_ingenieur'] = $user_data['off_geologue'] = 0;
        if(isset($_POST['c_off_ingenieur']) && $_POST['c_off_ingenieur'] == 'on') {
            $ingenieur = 1;
            $user_data['off_ingenieur'] = 1;
        }
        if(isset($_POST['c_off_geologue'])  && $_POST['c_off_geologue']  == 'on') {
            $geologue = 1;
            $user_data['off_geologue'] = 1;
        }
        $request = "UPDATE ".TABLE_USER." SET off_geologue='".$geologue."',";
        $request .= " off_ingenieur='".$ingenieur."'";
        $request .= " WHERE user_id = ".$user_data["user_id"];
        $db->sql_query($request);
	}
    // Classe collecteur
    if (isset($_POST['c_class_collect']) && $_POST['c_class_collect'] == 'on'){
	$class_collect = 1;
	$user_data["user_class"] = "COL";
	$request = "UPDATE ".TABLE_USER." SET user_class='COL'";
	$request .= " WHERE user_id = ".$user_data["user_id"];
	$db->sql_query($request);
	}
	
    if (isset($_POST['c_class_collect']) && $_POST['c_class_collect'] == 'off'){
	$class_collect = 0;
	$user_data["user_class"] = "none";
	$request = "UPDATE ".TABLE_USER." SET user_class='COL'";
	$request .= " WHERE user_id = ".$user_data["user_id"];
	$db->sql_query($request);
	}

//////////////////Manque gestion booster
}
/*** Fin enregistrement des données  ***/

/*** Récupération des données  ***/
$bati = array('','M','C','D','SoP','FR','SS','FO');

$user_empire = user_get_empire($user_data["user_id"]);
$user_building = $user_empire["building"];
if($user_empire["technology"]) $user_technology = $user_empire["technology"];
else $user_technology = '0';

// Pourcentages
$planet = array("planet_id" => "", "M_percentage" => 0, "C_percentage" => 0, "D_percentage" => 0, "CES_percentage" => 100, "CEF_percentage" => 100, "Sat_percentage" => 100, "FOR_percentage" => 100);
$quet = $db->sql_query("SELECT planet_id, M_percentage, C_percentage, D_percentage, CES_percentage, CEF_percentage, Sat_percentage, FOR_percentage FROM ".TABLE_USER_BUILDING." WHERE user_id = ".$user_data["user_id"]." AND planet_id < 199 ORDER BY planet_id");
$user_percentage = array_fill($start, $nb_planet, $planet);
while ($row = $db->sql_fetch_assoc($quet)) {
	$arr = $row;
	unset($arr["planet_id"]);
	$user_percentage[$row["planet_id"]] = $arr;
}
// ajout infos pour gestion js ...
$officier = $user_data['off_commandant'] + $user_data['off_amiral'] + $user_data['off_ingenieur']
          + $user_data['off_geologue'] + $user_data['off_technocrate'];
$off_full = ($officier == 5) ? '1' : '0';

$class_collect = ($user_data['user_class'] == 'COL') ? '1' : '0';
echo "<input type='hidden' id='vitesse_uni' size='2' maxlength='5' value='".$server_config['speed_uni']."'/>";
echo "<input type='hidden' id='off_ingenieur' value='".$user_data["off_ingenieur"]."'/>";
echo "<input type='hidden' id='off_geologue' value='".$user_data["off_geologue"]."'/>";
echo "<input type='hidden' id='off_full' value='".$off_full."'/>";
echo "<input type='hidden' id='class_collect' value='".$class_collect."'/>";

$vitesse = $server_config['speed_uni'];
//Binu : Correction calcul du nombre de cases utilisées
for ($i = $start ; $i <= $nb_planet ; $i++){
	$binu_fields_used[$i] = $user_building[$i]['M'] + $user_building[$i]['C'] + $user_building[$i]['D'] + $user_building[$i]['CES'] + $user_building[$i]['CEF'] + $user_building[$i]['UdR'] + $user_building[$i]['UdN'] + $user_building[$i]['CSp'] + $user_building[$i]['HM'] + $user_building[$i]['HC'] + $user_building[$i]['HD'] + $user_building[$i]['Lab'] + $user_building[$i]['Ter'] + $user_building[$i]['Ddr'] + $user_building[$i]['Silo'] + $user_building[$i]['BaLu'] + $user_building[$i]['Pha'] + $user_building[$i]['PoSa'];
}
//Fin correction

?>
<script type="text/javascript" src="js/ogame_formula.js"></script>
<!-- DEBUT DU SCRIPT -->
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
			$user_building[$i]['FOR']."','".
			$user_building[$i]['temperature_max']."','".
			$user_percentage[$i]['M_percentage']."','".
			$user_percentage[$i]['C_percentage']."','".
			$user_percentage[$i]['D_percentage']."','".
			$user_percentage[$i]['CES_percentage']."','".
			$user_percentage[$i]['CEF_percentage']."','".
			$user_percentage[$i]['Sat_percentage']."','".
			$user_percentage[$i]['FOR_percentage']."',1);\n";
	} else {
		$Planete[$i] = 0;
		echo "batimentsOGSpy[".$i."] = new Array('','','','','','','','','','','','','','','','',0);\n";
	}
}
echo "vitesse = ".$vitesse.";\n";
?>
bati = new Array('','M','C','D','SoP','FR','SS','FO');

function chargement() {
	temp = new Array('',9,10,11,12,13,14,15);
	for(i=start ; i<=nb_planet ; i++) {
		for(b=1 ; b<=7 ; b++) {
			document.getElementById(bati[b]+i).value = batimentsOGSpy[i][b];
			document.getElementById('rap_' + bati[b] + i).value = batimentsOGSpy[i][temp[b]];
		}
		document.getElementById('global' + (i-100)).checked = true;
	}
<?php
	echo "\tdocument.getElementById('techno_energie').value = ".$user_technology['NRJ'].";\n";
	echo "\tdocument.getElementById('techno_plasma').value = ".$user_technology['Plasma'].";\n";
	echo "\tdocument.getElementById('c_off_ingenieur').checked = ";
	if($user_data['off_ingenieur'] == 1) echo "true;\n";
	else echo "false;\n";
	echo "\tdocument.getElementById('c_off_geologue').checked = ";
	if($user_data['off_geologue'] == 1) echo "true;\n";
	else echo "false;\n";
	echo "\tdocument.getElementById('c_off_full').checked = ";
	if($off_full == 1) echo "true;\n";
	else echo "false;\n";
	echo "\tdocument.getElementById('c_class_collect').checked = ";
	if($user_data['user_class'] == "COL") echo "true;\n";
	else echo "false;\n";
?>
	verif_donnee ();
}

function add(bat, plan, inc) {
	if (bat == 8) {
		document.getElementById('techno_energie').value = parseFloat(document.getElementById('techno_energie').value) + inc;
	} else {
		if (bat == 9) {
			document.getElementById('techno_plasma').value = parseFloat(document.getElementById('techno_plasma').value) + inc;
		} else {
			document.getElementById(bati[bat] + plan).value = parseFloat(document.getElementById(bati[bat] + plan).value) + inc;
		}
	}
	verif_donnee ();
}

function selection(sel) {
	if (sel == 0) sel = false;
	else sel = true;
	for (i=1 ; i<=nb_planet-start+1 ; i++) document.getElementById('global' + i).checked = sel; 
	verif_donnee ();
}

function verif_donnee(envoye) {
	global = new Array();

	global[0] = 0;
	for(i=start ; i<=nb_planet ; i++) {
		global[i-100] = 1;
	}	
    for(i=start ; i<=nb_planet ; i++) {
		j = i - 100;
		for(b=1 ; b<=7 ; b++) {
			if((isNaN(parseFloat(document.getElementById(bati[b] + i).value))) || parseFloat(document.getElementById(bati[b] + i).value) < 0 ) document.getElementById(bati[b] + i).value = batimentsOGSpy[i][b];
		}
    }
	if((isNaN(parseFloat(document.getElementById('techno_energie').value))) || parseFloat(document.getElementById('techno_energie').value) < 0 ) document.getElementById('techno_energie').value = technologieNRJ;
	if((isNaN(parseFloat(document.getElementById('techno_plasma').value))) || parseFloat(document.getElementById('techno_plasma').value) < 0 ) document.getElementById('techno_plasma').value = technologiePlasma;

    
    document.getElementById('off_full').value = 0;
    document.getElementById('off_geologue').value = 0;
    document.getElementById('off_ingenieur').value = 0;
	if(document.getElementById('c_off_full').checked) {
	    document.getElementById('c_off_geologue').checked=true;
	    document.getElementById('c_off_ingenieur').checked=true;
        document.getElementById('off_full').value = 1;
        document.getElementById('off_geologue').value = 1;
        document.getElementById('off_ingenieur').value = 1;
	} else {
        if(document.getElementById('c_off_ingenieur').checked) {
            document.getElementById('off_ingenieur').value = 1;
        }
        if(document.getElementById('c_off_geologue').checked) {
            document.getElementById('off_geologue').value = 1;
        }
    }
	document.getElementById('class_collect').value = 0;
	if (document.getElementById('c_class_collect').checked) {
		document.getElementById('class_collect').value = 1;
	}
    
	if(envoye == 1) {
		document.getElementById('s_save').value = 1;
		document.forms.Save.submit();
	}
	else recup_donnee();
}

function recup_donnee () {
	donnee = new Array;
	
	for(b=1 ; b<=7 ; b++) {
		donnee[bati[b]] = new Array;
		donnee['rap_' + bati[b]] = new Array;
		for(i=start ; i<=nb_planet ; i++) {
			donnee[bati[b]][i] = parseFloat(document.getElementById(bati[b] + i).value);
			donnee['rap_' + bati[b]][i] = parseFloat(document.getElementById('rap_' + bati[b] + i).value);
		}
	}
	technologieNRJ = parseFloat(document.getElementById('techno_energie').value);
	technologiePlasma = parseFloat(document.getElementById('techno_plasma').value);
	calcul();
}



function calcul () {
ratio = new Array();
cases = new Array();

cases_base = new Array(0<?php
for ($i=$start ; $i<=$nb_planet ; $i++)
    echo ", ".($user_building[$i]["fields_used"] - $user_building[$i]['M'] - $user_building[$i]['C'] - $user_building[$i]['D'] - $user_building[$i]['CES'] - $user_building[$i]['CEF']);
?>);
var NRJ = technologieNRJ;
var Plasma = technologiePlasma;

var speed = <?php
echo $vitesse
?>;

energie = new Array();
energie_tot = new Array();
metal_heure = new Array();
cristal_heure = new Array();
deut_heure = new Array();
metal_heure[nb_planet+1] = 0;
cristal_heure[nb_planet+1] = 0;
deut_heure[nb_planet+1] = 0;


var M_1_prod = new Array();
var C_1_prod = new Array();
var D_1_prod = new Array();
var F_1_prod = new Array();
var M_prod = 0;
var C_prod = 0;
var D_prod = 0;
var F_prod_M = 0;
var F_prod_C = 0;
var F_prod_D = 0;
for(i=start ; i<=nb_planet ; i++) {
    if (batimentsOGSpy[i][16] == 1) {
        temperature_max_1 = batimentsOGSpy[i][8];
        
        var M_1_conso = Math.round(consumption("M", donnee['M'][i]) * donnee['rap_M'][i] / 100);
        var C_1_conso = Math.round(consumption("C", donnee['C'][i]) * donnee['rap_C'][i] / 100);
        var D_1_conso = Math.round(consumption("D", donnee['D'][i]) * donnee['rap_D'][i] / 100);
	
	var nb_F_1 = 0;	
	if ( donnee['FO'][i] > (donnee['M'][i] + donnee['C'][i] + donnee['D'][i]) * 8) {
		nb_F_1 = (donnee['M'][i] + donnee['C'][i] + donnee['D'][i]) * 8;
	} else {
		nb_F_1 = donnee['FO'][i];	
	}
	var F_1_conso = Math.round(consumption("FOR", nb_F_1) * donnee['rap_FO'][i] / 100);
        var energie_conso = M_1_conso + C_1_conso + D_1_conso + F_1_conso;
        
        var CES_1_production = production("CES", donnee['SoP'][i], temperature_max_1, NRJ) * donnee['rap_SoP'][i] / 100;
        var CEF_1_production = production("CEF", donnee['FR'][i], temperature_max_1, NRJ) * donnee['rap_FR'][i] / 100;
        var Sat_1_production = production_sat(temperature_max_1, donnee['SS'][i]) * donnee['rap_SS'][i] / 100;
        if(isNaN(Sat_1_production)) Sat_1_production = production_sat(temperature_max_1, temperature_max_1-40) * donnee['SS'][i] * donnee['rap_SS'][i] / 100;
        var NRJ_1 = Math.round(CES_1_production + CEF_1_production + Sat_1_production);
        
        var NRJ_1_delta = NRJ_1 - energie_conso;
        if(isNaN(NRJ_1)) NRJ_1 = 0;
        
        //Ratio de consommation d'énergie
        var ratio_conso = 0;
        if(energie_conso != 0) {
            var ratio_conso = NRJ_1 / energie_conso;
            if(ratio_conso > 1) ratio_conso = 1;
        }
        if(ratio_conso > 0){
	if (class_collect.value == "1") {
	F_prod_M = Math.round(ratio_conso * nb_F_1 * 0.0003 * Math.floor(speed * 30 * donnee['M'][i] * Math.pow(1.1,donnee['M'][i]) * (1)));
	F_prod_C = Math.round(ratio_conso * nb_F_1 * 0.0003 * Math.floor(speed * 20 * donnee['C'][i] * Math.pow(1.1,donnee['C'][i]) * (1)));
	F_prod_D = Math.round(ratio_conso * nb_F_1 * 0.0003 * Math.floor(speed * 10 * donnee['D'][i] * Math.pow(1.1,donnee['D'][i]) * (1.44 - 0.004 * temperature_max_1 ) * (1)));
	} else {


	F_prod_M = Math.round(ratio_conso * nb_F_1 * 0.0002 * Math.floor(speed * 30 * donnee['M'][i] * Math.pow(1.1,donnee['M'][i]) * (1)));
	F_prod_C = Math.round(ratio_conso * nb_F_1 * 0.0002 * Math.floor(speed * 20 * donnee['C'][i] * Math.pow(1.1,donnee['C'][i]) * (1)));
	F_prod_D = Math.round(ratio_conso * nb_F_1 * 0.0002 * Math.floor(speed * 10 * donnee['D'][i] * Math.pow(1.1,donnee['D'][i]) * (1.44 - 0.004 * temperature_max_1 ) * (1)));
	}



            M_1_prod[i] = Math.round(ratio_conso * production("M", donnee['M'][i], temperature_max_1, NRJ, Plasma) * donnee['rap_M'][i] / 100) + F_prod_M;
            C_1_prod[i] = Math.round(ratio_conso * production("C", donnee['C'][i], temperature_max_1, NRJ, Plasma) * donnee['rap_C'][i] / 100) + F_prod_C;
            D_1_prod[i] = Math.round(ratio_conso * production("D", donnee['D'][i], temperature_max_1, NRJ, Plasma) * donnee['rap_D'][i] / 100) - Math.round(consumption("CEF", donnee['FR'][i]) * donnee['rap_FR'][i] / 100) + F_prod_D;
        } else {
            M_1_prod[i] = Math.round(production("M", 0, 0, 0));
            C_1_prod[i] = Math.round(production("C", 0, 0, 0));
            D_1_prod[i] = Math.round(production("D", 0, 0, 0));
        }
        
        
        prod_energie = NRJ_1;
        cons_energie = energie_conso;
        energie[i] = NRJ_1_delta;
        energie_tot[i] = prod_energie;
        ratio[i] = Math.floor(ratio_conso * 100)/100;
        metal_heure[i] = M_1_prod[i];
        cristal_heure[i] = C_1_prod[i];
        deut_heure[i] = D_1_prod[i];
        
        var j = i-100;
        if (global[j] == 1) {
		if (document.getElementById('global' + j).checked) {
            	M_prod = M_prod + M_1_prod[i];
            	C_prod = C_prod + C_1_prod[i];
            	D_prod = D_prod + D_1_prod[i];
		}
        }
		//Binu retrait des 100 cases ajoutées pour afficher correctement la couleur
		cases[i] = cases_base[j] + donnee['M'][i] + donnee['C'][i] + donnee['D'][i] + donnee['SoP'][i] + donnee['FR'][i] - 100;
		//Fin correction
    }
}

metal_heure[nb_planet+1] = M_prod;
cristal_heure[nb_planet+1] = C_prod;
deut_heure[nb_planet+1] = D_prod;

ecrire();
}

function ecrire() {
<?php
for ($i=$start;$i<=$nb_planet;$i++) {
	if ($Planete[$i] == 1) {
		echo "if (batimentsOGSpy['".$i."'][16] == 1) {\n";
		echo "\tif (ratio[".$i."] == 1) couleur = 'lime';\n\telse couleur = 'red';\n";
		// echo "\tif (cases[".$i."] <= ".$user_building[$i]["fields"].") couleur2 = 'lime';\n\telse couleur2 = 'red';\n";
		echo "\tif (cases[".$i."] <= ".$binu_fields_used[$i].") couleur2 = 'lime';\n\telse couleur2 = 'red';\n";
		echo "\tdocument.getElementById('fact".$i."').innerHTML = '<font color=\'' + couleur + '\'>' + ratio[".$i."] + '</font>';\n";
		// echo "\tdocument.getElementById('cases".$i."').innerHTML = '<font color=\'' + couleur2 + '\'>' + format(".$user_building[$i]["fields_used"].") + '</font>';\n";
		echo "\tdocument.getElementById('cases".$i."').innerHTML = '<font color=\'' + couleur2 + '\'>' + format(".$binu_fields_used[$i].") + '</font>';\n";
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
<!-- FIN DU SCRIPT -->

<form name="Save" method="post" action="">
<input id='s_save' type='hidden' value='0'>
<table id="simu" width="100%">
<tr>
	<td class="c"><?php echo $lang['prod_uction_mod'];?>&nbsp;
		<input type='submit' value='<?php echo $lang['prod_save'];?>' onClick='javascript:verif_donnee(1)'>&nbsp;
		<input type='submit' value='<?php echo $lang['prod_reset'];?>' onClick="javascript:chargement()">
	</td>
</tr>
<tr>
<?php
echo "\t<th><a>".$lang['prod_planete']."</a></th>\n";   //Planète
for ($i=$start ; $i<=$nb_planet ; $i++) {
	$name[$i] = $user_building[$i]["planet_name"];
	if($name[$i] == "") $name[$i] = "&nbsp;";
	echo "\t<th><a>".$name[$i]."</a></th>\n";
}
?>
</tr>
<tr>
<?php
echo "\t<th><a>".$lang['prod_coordinates']."</a></th>\n";   //Coordonées
for ($i=$start ; $i<=$nb_planet ; $i++) {
	$coordinates = $user_building[$i]["coordinates"];
	if ($coordinates == "") $coordinates = "&nbsp;";
	else $coordinates = "[".$coordinates."]";
	echo "\t<th>".$coordinates."</th>\n";
}
?>
</tr>
<tr>
<?php
echo "\t<th><a>".$lang['prod_temperature']."</a></th>\n";   //Température maximale
for ($i=$start ; $i<=$nb_planet ; $i++) {
	$temperature_max = $user_building[$i]["temperature_max"];
	if ($temperature_max == "") $temperature_max = "&nbsp;";
    echo "\t<th>".$temperature_max."<input id='temperature_max_".$i."' type='hidden' value='".$temperature_max."'></th>"."\n";
}
?>
</tr>
<tr>
<?php
echo "\t<th><a>".$lang['prod_fields']."</a></th>\n";    //Cases
for ($i=$start ; $i<=$nb_planet ; $i++) {
    $fields = $user_building[$i]["fields"];
	if ($fields == "0") $fields = "?";
    $correction_fields = $fields-100;
    echo "\t<th><font color='lime'><span id='cases".$i."'>".$fields."</span></font> / ";
    echo "<font color='lime'><span id='cases_tot".$i."'>".$user_building[$i]["fields_used"]."</span></font></th>\n";
}
?>
</tr>
<tr>
<?php
echo "\t<th><a>".$lang['prod_energy']."</a></th>\n";    //Énergie
for ($i=$start ; $i<=$nb_planet ; $i++) {
    // echo "\t<th><font color='lime'><div id='NRJ_".$i."'>-</div></font></th>"."\n";
    echo "\t<th><font color='lime'><span id='energie".$i."'></span></font> / <font color='lime'><span id='energie_tot".$i."'></span></font></th>\n";
}
?>
</tr>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>"><?php echo $lang['prod_buildings'];?></td>
</tr>
<?php
for ($b=1 ; $b<=5 ; $b++) {
	echo "<tr><th><a>".$lang['prod_building_'.$bati[$b]]."</a></th>";
	for ($i=$start;$i<=$nb_planet;$i++) {
		echo "\t<th><a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(".$b.",".$i.",-1)'>-</a>";
        echo "<input type='text' id='".$bati[$b].$i."' name='".$bati[$b].$i."' size='2' maxlength='2' onBlur='javascript:verif_donnee(0)' value='0'>";
        echo "<a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(".$b.",".$i.",1)'>+</a>\n";
		echo "\t\t<select id='rap_".$bati[$b].$i."' name='rap_".$bati[$b].$i."' onChange='javascript:verif_donnee(0)'>";
		for ($j=100 ; $j>=0 ; $j=$j-10) echo "<option value='".$j."'>".$j."%</option>";
		echo "</select></th>\n";
	}
	echo "</tr>";
}
echo "<tr><th><a>".$lang['prod_SS']."</a></th>";
	for ($i=$start ; $i<=$nb_planet ; $i++) {
		echo "\t<th><a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(6,".$i.",-1)'>-</a>";
        echo "<input type='text' id='".$bati[6].$i."' name='".$bati[6].$i."' size='2' maxlength='6' onBlur='javascript:verif_donnee(0)' value='0'>";
        echo "<input type='hidden' name='planete".$i."' value='".$Planete[$i]."'>";
        echo "<a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(6,".$i.",1)'>+</a>\n";
		echo "\t\t<select id='rap_".$bati[6].$i."' name='rap_".$bati[6].$i."' onChange='javascript:add (6,".$i.",1)'>";
		for ($j=100 ; $j>=0 ; $j=$j-10) echo "<option value='".$j."'>".$j."%</option>";
		echo "</select></th>\n";
	}
	echo "</tr>";


echo "<tr><th><a>".$lang['prod_FO']."</a></th>";
	for ($i=$start ; $i<=$nb_planet ; $i++) {
		echo "\t<th><a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(7,".$i.",-1)'>-</a>";
        echo "<input type='text' id='".$bati[7].$i."' name='".$bati[7].$i."' size='2' maxlength='6' onBlur='javascript:verif_donnee(0)' value='0'>";
        echo "<input type='hidden' name='planete".$i."' value='".$Planete[$i]."'>";
        echo "<a style='cursor: pointer; vertical-align: middle;' onClick='javascript:add(7,".$i.",1)'>+</a>\n";
		echo "\t\t<select id='rap_".$bati[7].$i."' name='rap_".$bati[6].$i."' onChange='javascript:add (7,".$i.",1)'>";
		for ($j=100 ; $j>=0 ; $j=$j-10) echo "<option value='".$j."'>".$j."%</option>";
		echo "</select></th>\n";
	}
	echo "</tr>";




?>
<tr>
<td class="c" colspan="<?php echo $nb_planet;?>"><?php echo $lang['prod_tech_off'];?></td>
</tr>
<tr><th><a><?php echo $lang['prod_technology_En'];?></a></th>
	<th><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add(8,0,-1)'>-</a><input type='text' id='techno_energie' name='techno_energie' size='2' maxlength='6' onBlur='javascript:verif_donnee(0)' value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (8,0,1)'>+</a></th>
	<th><a><?php echo $lang['prod_technology_Pl'];?></a></th>
	<th><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (9,0,-1)'>-</a><input type='text' id='techno_plasma' name='techno_plasma' size='2' maxlength='6' onBlur='javascript:verif_donnee(0)' value='0'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:add (9,0,1)'>+</a></th>
	<th colspan="2" title="<?php echo $lang['prod_officer_E_help'];?>"><label><a><?php echo $lang['prod_officer_E'];?></a><input type='checkbox' id='c_off_ingenieur' name='c_off_ingenieur' onClick='javascript:verif_donnee(0)'></label></th>
	<th colspan="2" title="<?php echo $lang['prod_officer_G_help'];?>"><label><a><?php echo $lang['prod_officer_G'];?></a><input type='checkbox' id='c_off_geologue' name='c_off_geologue' onClick='javascript:verif_donnee(0)'></label></th>
	<th colspan="2" title="<?php echo $lang['prod_officer_full_help'];?>"><label><a><?php echo $lang['prod_officer_full'];?></a><input type='checkbox' id='c_off_full' name='c_off_full' onClick='javascript:verif_donnee(0)'></label></th>
	<th colspan="2" title="<?php echo $lang['prod_collect_help'];?>"><label><a><?php echo $lang['prod_collect'];?></a><input type='checkbox' id='c_class_collect' name='c_class_collect' onClick='javascript:verif_donnee(0)'></label></th>
</tr>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_prod_hour']."</td>\n</tr>\n<tr>\n<th><a>".$lang['prod_prod_factor']."</a></th>\n";
for ($i=$start ; $i<=$nb_planet ; $i++) echo "\t<th><span id='fact".$i."'></span></th>\n";
?>
</tr>
<?php
for ($b=1 ; $b<=3 ; $b++) {
	echo "<tr><th><a>".$lang['prod_building_'.$bati[$b]]."</a></th>\n";
	for ($i=$start ; $i<=$nb_planet ; $i++) echo "\t<th><span id='prodh_".$bati[$b].$i."'></span></th>\n";
	echo "</tr>";
}
?>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_prod_day']."</td>\n</tr>\n";
for ($b=1 ; $b<=3 ; $b++) {
	echo "<tr><th><a>".$lang['prod_building_'.$bati[$b]]."</a></th>\n";
	for ($i=$start ; $i<=$nb_planet ; $i++) echo "\t<th><span id='prodj_".$bati[$b].$i."'></span></th>\n";
	echo "</tr>";
}
?>
<tr>
	<td class="c" colspan="<?php echo $nb_planet+1;?>">
<?php
echo $lang['prod_total_prod']."</td>\n</tr>\n<tr><th><table width='100%' style='border:none'><tr>";
echo "<th style='border:none'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:selection(0)' title='".$lang['prod_none']."'>-</a></th>";
echo "<th style='border:none'><a>".$lang['prod_account']."</a></th>";
echo "<th style='border:none'><a style='cursor: pointer;vertical-align: middle;' onClick='javascript:selection(1)' title='".$lang['prod_all']."'>+</a></th></tr></table></th>\n";
for ($i=$start ; $i<=$nb_planet ; $i++) {
    $j=$i - $start + 1;
	echo "\t<th><label><input type='";
	if ($Planete[$i] == 1) echo "checkbox";
	else echo "hidden";
	echo "' id='global$j' name='global$j' onClick='javascript:verif_donnee(0)'>&nbsp;$name[$i]</label></th>\n";
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
	<th onClick="javascript:func_rapport(3)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_M'];?></a></th>
	<th onClick="javascript:func_rapport(3)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_m'></span></font></th>
	<th onClick="javascript:func_rapport(2)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_C'];?></a></th>
	<th onClick="javascript:func_rapport(2)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_c'></span></font></th>
	<th onClick="javascript:func_rapport(1)" title="<?php echo $lang['prod_ref'];?>"><img style="cursor: help;" src="images/help_2.png" alt="?"> <a><?php echo $lang['prod_D'];?></a></th>
	<th onClick="javascript:func_rapport(1)" title="<?php echo $lang['prod_ref'];?>"><font color='lime'><span id='rapport_d'></span></font></th>
</tr>
</table>
</form>
<br/>
<?php
echo "<div align=center><font size='2'>".sprintf($lang['prod_created_by'],$mod_version,$creator_name,$modifier_name1,$modifier_name2,$modifier_name3)."</font><br />".
"<div align=center><font size='2'>".sprintf($lang['prod_updated_by'],$mod_version,$updator_name)."</font><br />".
	"<font size='1'><a href='".$forum_link."' target='_blank'>".$lang['prod_forum']."</a>.</font></div>";
require_once("views/page_tail.php");
?>
