<?php
/**
 * Les données pour la MAJ 1.6.0
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

/**
 * Si vous faites une mise à jour sur une de ses actions, il faut obligatoirement préciser pourquoi.
 * 1) Incrémenter la version avec la version courante.
 * 2) Faites un descriptif de votre modification.
 *
 * Exemples:
 * 'action'            => 'task_manager_update_1600_task_compiled_time',
 * 'description'       => __( 'Create compiled time for all tasks.', 'frais-pro' ),
 * 'since'             => '1.6.0',
 * 'version'           => '1.7.0',
 * 'description_1.7.0' => 'Correction du warning de l'index du tableau X..'
 */

$datas = array(
	array(
		'action'      => 'frais_pro_update_1400_change_statuses_storage',
		'description' => __( 'Change the status for update action', 'frais-pro' ),
		'since'       => '1.4.0',
		'version'     => '1.4.0',
	),
);
