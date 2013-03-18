<?php
/**
 * Plugin Domuni
 * (c) 2013 Rainer Müller
 * Licence GNU/GPL
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function mcp_orr_affiche_milieu($flux) {
    $texte = "";
    $e = trouver_objet_exec($flux['args']['exec']);



    // orr_ressources sur les articles
    if (!$e['edition'] AND in_array($e['type'], array('article'))) {
        $texte .= recuperer_fond('prive/objets/editer/liens', array(
            'table_source' => 'orr_ressources',
            'objet' => $e['type'],
            'id_objet' => $flux['args'][$e['id_table_objet']]
        ));
    }

    if ($texte) {
        if ($p=strpos($flux['data'],"<!--affiche_milieu-->"))
            $flux['data'] = substr_replace($flux['data'],$texte,$p,0);
        else
            $flux['data'] .= $texte;
    }

    return $flux;
}

/**
 * Ajouter les objets sur les vues de rubriques
 *
 * @pipeline affiche_enfants
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
**/
function mcp_orr_affiche_enfants($flux) {
    if ($e = trouver_objet_exec($flux['args']['exec'])
        AND $e['type'] == 'rubrique'
        AND $e['edition'] == false) {

        $id_rubrique = $flux['args']['id_rubrique'];
        $lister_objets = charger_fonction('lister_objets', 'inc');

        $bouton = '';
        if (autoriser('creerorr_ressourcedans', 'rubrique', $id_rubrique)) {
            $bouton .= icone_verticale(_T("orr_ressource:icone_creer_orr_ressource"), generer_url_ecrire("orr_ressource_edit", "id_rubrique=$id_rubrique"), "orr_ressource-24.png", "new", "right")
                    . "<br class='nettoyeur' />";
        }

        $flux['data'] .= $lister_objets('orr_ressources', array('titre'=>_T('orr_ressource:titre_orr_ressources_rubrique') , 'id_rubrique'=>$id_rubrique, 'par'=>'orr_ressource_nom'));
        $flux['data'] .= $bouton;

    }
    return $flux;
}

function mcp_orr_formulaire_charger($flux){
 $form=$flux['args']['form'];
 
  
 // cré un contact si pas encore existant
 if($form == 'editer_orr_ressource'){
        if(!isset($flux['data']['texte']))$flux['data']['texte']='';
        if(!isset($flux['data']['descriptif']))$flux['data']['descriptif']='';        
    }

 
 // cré un contact si pas encore existant
 if($form == 'inscription_client'
         and _request('page') == 'shop'
         and _request('appel') == 'mes_coordonnees'
       ){
    if($id_auteur = verifier_session()){
        $inscrire_client = charger_fonction('traiter','formulaires/inscription_client');
        $inscrire_client();
        }
    }
     return $flux;
}


function mcp_orr_formulaire_traiter($flux){
    // Si on est sur le formulaire client qui est sur la page identification
    $form=$flux['args']['form'];
    
    if($form == 'editer_orr_ressource'){
        $valeurs=array(
        'texte'=>_request('texte'),
        'descriptif'=>_request('texte'),
        );
        sql_updateq('spip_orr_ressources',$valeurs,'id_orr_ressource='.$flux['data']['id_orr_ressource']);
        
    }
    
    if($form == 'editer_client'
         and _request('page') == 'shop'
         and _request('appel') == 'mes_coordonnees'
         and include_spip('inc/paniers')
         and paniers_id_panier_encours()

       ){
        // On recupere d'abord toutes les informations dont on va avoir besoin
        // Deje le visiteur connecte
        $id_auteur = session_get('id_auteur');
    
        // On cree la commande ici
        include_spip('inc/commandes');
        $id_commande = creer_commande_encours();
        
        // On cherche l'adresse principale du visiteur
        $id_adresse = sql_getfetsel( 'id_adresse',  'spip_adresses_liens',
                         array( 'objet = '.sql_quote('auteur'),
                        'id_objet = '.intval($id_auteur),
                        'type = '.sql_quote('principale') ) );
        
        $adresse = sql_fetsel('*', 'spip_adresses', 'id_adresse = '.$id_adresse);
        unset($adresse['id_adresse']);
        
        // On copie cette adresse comme celle de facturation
        $id_adresse_facturation = sql_insertq('spip_adresses', $adresse);
        sql_insertq( 'spip_adresses_liens',
                        array( 'id_adresse' => $id_adresse_facturation,
                                'objet' => 'commande',
                                'id_objet' => $id_commande,
                                'type' => 'facturation' ) );
    
        // On copie cette adresse comme celle de livraison
        $id_adresse_livraison = sql_insertq('spip_adresses', $adresse);
        sql_insertq( 'spip_adresses_liens',
                        array( 'id_adresse' => $id_adresse_livraison,
                                'objet' => 'commande',
                                'id_objet' => $id_commande,
                                'type' => 'livraison' ) );
    }


    return($flux);
}

function mcp_orr_optimiser_base_disparus($flux){
    include_spip('action/editer_liens');
    $flux['data'] += objet_optimiser_liens(array('orr_ressource'=>'*'),'*');
    return $flux;
}
?>