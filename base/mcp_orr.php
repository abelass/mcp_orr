<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Mcp Orr
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Mcp_orr\Pipelines
 */


if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 */
function mcp_orr_declarer_tables_interfaces($interfaces) {

	$interfaces['tables_jointures']['spip_orr_ressources'][] = 'spip_orr_ressources_liens';

	$interfaces['table_des_tables']['orr_ressources_liens'] = 'orr_ressources_liens';

	return $interfaces;
}





/**
 * Déclaration des tables secondaires (liaisons)
 */
function mcp_orr_declarer_tables_auxiliaires($tables) {

	$tables['spip_orr_ressources_liens'] = array(
		'field' => array(
			"id_orr_ressource" => "bigint(21) DEFAULT '0' NOT NULL",
			"id_objet"           => "bigint(21) DEFAULT '0' NOT NULL",
			"objet"              => "VARCHAR(25) DEFAULT '' NOT NULL",
			"vu"                 => "VARCHAR(6) DEFAULT 'non' NOT NULL"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_orr_ressource,id_objet,objet",
			"KEY id_orr_ressource" => "id_orr_ressource"
		)
	);

    	return $tables;
}


?>
