<?php
/**
 * Préchargement des formulaires d'édition de orr_ressource
 *
 * @plugin     Mcp Orr
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Mcp_orr\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/precharger_objet');

/**
 * Retourne les valeurs à charger pour un formulaire d'édition d'un orr_ressource
 *
 * Lors d'une création, certains champs peuvent être préremplis
 * (c'est le cas des traductions) 
 *
 * @param string|int $id_orr_ressource
 *     Identifiant de orr_ressource, ou "new" pour une création
 * @param int $id_rubrique
 *     Identifiant éventuel de la rubrique parente
 * @param int $lier_trad
 *     Identifiant éventuel de la traduction de référence
 * @return array
 *     Couples clés / valeurs des champs du formulaire à charger.
**/
function inc_precharger_orr_ressource_dist($id_orr_ressource, $id_rubrique=0, $lier_trad=0) {
	return precharger_objet('orr_ressource', $id_orr_ressource, $id_rubrique, $lier_trad, 'orr_ressource_nom');
}

/**
 * Récupère les valeurs d'une traduction de référence pour la création
 * d'un orr_ressource (préremplissage du formulaire). 
 *
 * @note
 *     Fonction facultative si pas de changement dans les traitements
 * 
 * @param string|int $id_orr_ressource
 *     Identifiant de orr_ressource, ou "new" pour une création
 * @param int $id_rubrique
 *     Identifiant éventuel de la rubrique parente
 * @param int $lier_trad
 *     Identifiant éventuel de la traduction de référence
 * @return array
 *     Couples clés / valeurs des champs du formulaire à charger
**/
function inc_precharger_traduction_orr_ressource_dist($id_orr_ressource, $id_rubrique=0, $lier_trad=0) {
	return precharger_traduction_objet('orr_ressource', $id_orr_ressource, $id_rubrique, $lier_trad, 'orr_ressource_nom');
}


?>