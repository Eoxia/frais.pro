<?php
/**
 * Vue principale de l'application
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<section class="eox-note-frais">

	<h1>Mes notes de frais</h1>
	<div class="button blue"><i class="icon ion-plus-round"></i><span>Ajout</span></div>

</section>
<?php Group_NDF_Class::g()->display();
