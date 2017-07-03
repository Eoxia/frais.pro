<?php

namespace note_de_frais;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul class="row">
	<li class="date"><span contenteditable="true"><?php echo $ndf->date_modified; ?></span></li>
	<li class="libelle"><span contenteditable="true"><?php echo esc_html( $ndf->title ); ?></span></li>
	<li class="type"><span contenteditable="true"></span></li>
	<li class="km"><span contenteditable="true"></span></li>
	<li class="ttc"><span contenteditable="true"><?php echo esc_html( $ndf->TaxInclusiveAmount ); ?></span></li>
	<li class="ht"><span contenteditable="true"><?php echo esc_html( $ndf->TaxableAmount ); ?></span></li>
	<li class="tva"><span contenteditable="true"><?php echo esc_html( $ndf->TaxAmount ); ?></span></li>
	<li class="photo"><span contenteditable="true"></span></li>
	<li class="action"><span class="icon ion-trash-a"></span></li>
</ul>
