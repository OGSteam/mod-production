[MOD] Production - Partie explication

I/ Définition
"Production" est un module d'OGSpy permettant de connaître et de simuler sa production minière et énergétique.
Il permet également d'enregistrer les informations affichées. (C'est le seul endroit à ma connaissance qui peut prendre en compte le % de production)

Missions :
 M1 : Déterminer sa production minière (Totale et par planète)(par heure/jour/semaine)
 M2 : Enregistrer le % de production de chaque planète
 M3 : Faire des simulations de sa production minière ou énergétique


II/ Installation
1) Pré-requis
Le module "Production" ne modifie pas la base de donnée à l'installation.
Lors de l'utilisation, sur décision, il peut la modifier concernant le niveau des bâtiments miniers et énergétiques, les pourcentages de production et 2 technologies.
(Mine de métal, Mine de cristal, Synthétiseur de deutérium, Centrale électrique solaire, Centrale électrique de fusion, Satellite solaire, Technologie énergie, Technologie Plasma)

Intégration dans AutoUpdate : OUI

Pré-Requis Serveur :
    Avoir un serveur OGSpy

Pré-Requis Client :
    Activation de Javascript dans le navigateur Internet.

2) Options à configurer
Le module "Production" n'a aucune option.

 
III/ Utilisation
Les paramètres sont les données que l'on peut donner au module.
Les résultats sont les calculs et simulation du module par rapport aux paramètres données.

1) Paramètres
  Récupération initiale des données à partir des informations utilisateur contenues dans OGSpy.
  Pour chaque planète :
    - Niveau de bâtiment minier (Mine de métal, Mine de cristal, Synthètiseur de deuterium)
    - Niveau de bâtiment énergie (Centrale électrique solaire, Centrale électrique de fusion, Satellite solaire(nombre))
    - Pourcentage de production pour chaque bâtiment miner ou énergie
  Global :
    - Niveau de la technologie énergie
    - Niveau de la technologie plasma
    - Présence d'officier (Officier ingénieur, Officier géologue, Full officier)
    - Sélection des planètes pour le calcul de la production globale

2) Résultats
  Pour chaque planète :
    - énergie restante / énergie totale
    - Facteur de production
    - Production minière (Métal, Cristal, Deuterium) par heure et par jour
  Global :
    - Production minière (Métal, Cristal, Deuterium) par heure, jour et semaine
    - Production minière en équivalence points,  par heure, jour et semaine
    - Rapport entre métal/cristal/deuterieum


IV/ Versions
1.5.0
1.5.1 [2013-02-10]= Prise en compte des officiers et technologie plasma (+reprise du mod par Pitch314)
1.5.2 [2013-03-26]= Amélioration affichage
1.5.3 [2015-06-29]= Amélioration, refacctoring js + use OGSpy js
1.5.4 [2017-04-15]= La fonction requiert un user_id
1.5.5 [2018-10-23]= Version OGSpy Minimale
1.5.6 [2019-09-18]= Nouveau fichier de gestion
1.5.7 [2020-03-16]= Nouveau fichier de gestion
1.5.8 [2020-xx-xx]= Nouveau fichier de gestion

V/ Informations et liens
Les bugs seront renseignés sur github (https://github.com/OGSteam/mod-production/issues/new) ou sur le forum avec comme objet :
" [Mod] Production, bug/erreur/problème ...] " (choisir le bon mot)

Bitbucket : https://github.com/OGSteam/mod-production
Signalement de bugs : https://github.com/OGSteam/mod-production/issues/new
Téléchargement : https://github.com/OGSteam/mod-production/archive/master.zip
Wiki OGSteam : http://wiki.ogsteam.fr/doku.php?id=ogspy:liste_mods#production

Responsable du mod : Pitch314
