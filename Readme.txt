[MOD] Production - Partie explication

I/ D�finition
"Production" est un module d'OGSpy permettant de conna�tre et de simuler sa production mini�re et �nerg�tique.
Il permet �galement d'enregistrer les informations affich�es. (C'est le seul endroit � ma connaissance qui peut prendre en compte le % de production)

Missions :
 M1 : D�terminer sa production mini�re (Totale et par plan�te)(par heure/jour/semaine)
 M2 : Enregistrer le % de production de chaque plan�te
 M3 : Faire des simulations de sa production mini�re ou �nerg�tique


II/ Installation
1) Pr�-requis
Le module "Production" ne modifie pas la base de donn�e � l'installation.
Lors de l'utilisation, sur d�cision, il peut la modifier concernant le niveau des b�timents miniers et �nerg�tiques, les pourcentages de production et 2 technologies.
(Mine de m�tal, Mine de cristal, Synth�tiseur de deut�rium, Centrale �lectrique solaire, Centrale �lectrique de fusion, Satellite solaire, Technologie �nergie, Technologie Plasma)

Int�gration dans AutoUpdate : OUI

Pr�-Requis Serveur :
    Avoir un serveur OGSpy

Pr�-Requis Client :
    Activation de Javascript dans le navigateur Internet.

2) Options � configurer
Le module "Production" n'a aucune option.

 
III/ Utilisation
Les param�tres sont les donn�es que l'on peut donner au module.
Les r�sultats sont les calculs et simulation du module par rapport aux param�tres donn�es.

1) Param�tres
  R�cup�ration initiale des donn�es � partir des informations utilisateur contenues dans OGSpy.
  Pour chaque plan�te :
    - Niveau de b�timent minier (Mine de m�tal, Mine de cristal, Synth�tiseur de deut�rium)
    - Niveau de b�timent �nergie (Centrale �lectrique solaire, Centrale �lectrique de fusion, Satellite solaire(nombre))
    - Pourcentage de production pour chaque b�timent miner ou �nergie
  Global :
    - Niveau de la technologie �nergie
    - Niveau de la technologie plasma
    - Pr�sence d'officier (Officier ing�nieur, Officier g�ologue, Full officier)
    - S�lection des plan�tes pour le calcul de la production globale

2) R�sultats
  Pour chaque plan�te :
    - �nergie restante / �nergie totale
    - Facteur de production
    - Production mini�re (M�tal, Cristal, Deut�rium) par heure et par jour
  Global :
    - Production mini�re (M�tal, Cristal, Deut�rium) par heure, jour et semaine
    - Production mini�re en �quivalence points,  par heure, jour et semaine
    - Rapport entre m�tal/cristal/deut�rieum


IV/ Versions
1.5.0
1.5.1 [2013-02-10]= Prise en compte des officiers et technologie plasma (+reprise du mod par Pitch314)
1.5.2 [2013-03-26]= Am�lioration affichage


V/ Informations et liens
Les bugs seront renseign�s sur bitbucket (https://bitbucket.org/pitch314/mod-production/issues/new) ou sur le forum avec comme objet :
" [Mod] Production, bug/erreur/probl�me ...] " (choisir le bon mot)

Bitbucket : https://bitbucket.org/pitch314/mod-production
Signalement de bugs : https://bitbucket.org/pitch314/mod-production/issues/new
T�l�chargement : https://bitbucket.org/pitch314/mod-production/downloads#tag-downloads
Wiki OGSteam : http://wiki.ogsteam.fr/doku.php?id=ogspy:liste_mods#production

Responsable du mod : Pitch314