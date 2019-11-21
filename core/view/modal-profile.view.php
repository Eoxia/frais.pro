<?php
/**
 * Modal contenant le formulaire pour renseigner son profile
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   FraisPro\Templates
 *
 * @since     1.5.0
 */
namespace frais_pro;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-form">
	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Firstname' ); ?></span>
		<label class="form-field-container">
			<input type="text" name="firstname" class="form-field" value="<?php echo esc_attr( $data['firstname'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Lastname' ); ?></span>
		<label class="form-field-container">
			<input type="text" name="lastname" class="form-field" value="<?php echo esc_attr( $data['lastname'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Brand of the car', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<input type="text" name="marque" value="<?php echo esc_attr( $data['marque'] ); ?>" class="form-field" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Tax horsepower', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<select name="chevaux" id="chevaux" lass="form-field">
				<?php

				if ( ! empty( \eoxia\Config_Util::$init['frais-pro']->chevaux ) ) :
					foreach ( \eoxia\Config_Util::$init['frais-pro']->chevaux as $chevaux ) :
						?>
						<option value="<?php echo esc_attr( $chevaux ); ?>" <?php selected( $chevaux, $data['chevaux'], true ); ?>><?php echo esc_html( $chevaux ); ?></option>
						<?php
					endforeach;
				endif;
				?>
			</select>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Price per Kilometer', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<input type="text" name="prixkm" value="<?php echo esc_attr( $data['prixkm'] ); ?>" class="form-field" />
		</label>
	</div>


	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Registration document scan', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<?php echo do_shortcode( '[wpeo_upload id="' . $data['id'] . '" model_name="/frais_pro/User_Class"]' ); ?>
		</label>
	</div>

	<div class="form-element form-align-horizontal">
		<span class="form-label"><?php esc_html_e( 'Default display mode', 'frais-pro' ); ?></span>
		<label class="form-field-container">
			<div class="form-field-inline">
				<input type="radio" id="grid" class="form-field" name="default_display_mode" <?php checked( $data['default_display_mode'], 'grid', true ); ?> value="grid">
				<label for="grid"><?php esc_html_e( 'Grid mode', 'frais-pro' ); ?></label>
			</div>
			<div class="form-field-inline">
				<input type="radio" id="list" class="form-field" name="default_display_mode" <?php checked( $data['default_display_mode'], 'list', true ); ?> value="list">
				<label for="list"><?php esc_html_e( 'List mode', 'frais-pro' ); ?></label>
			</div>
		</label>
	</div>

	<?php if ( ( get_current_user_id() !== $data['id'] ) || ( 1 === get_current_user_id() ) ) : ?>
		<div class="form-element form-align-horizontal">
			<label class="form-field-container">
				<div class="form-field-inline">
					<input type="checkbox" id="ndf_admin" class="form-field" name="ndf_admin" <?php checked( $data['ndf_admin'], true, true ); ?> value="true">
					<label for="ndf_admin"><?php esc_html_e( 'User is allowed to view all fees sheet', 'frais-pro' ); ?></label>
				</div>
			</label>
		</div>
	<?php endif; ?>

</div>
