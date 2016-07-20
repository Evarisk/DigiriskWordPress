<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<section class="gridwrapper2">
	<!-- Le bloc des utilisateurs affectés -->
	<div>

		<?php do_shortcode( '[digi-search id="' . $workunit->id . '" next-action="display_user_affected" type="user" target="wp-digi-list-affected-user"]' ); ?>
		<!-- La liste des utilisateurs affectés -->
		<?php require( USERS_VIEW . '/list-affected-user.php' ); ?>
	</div>

	<!-- Le bloc des utilisateurs à affecter -->
	<div>


		<!-- La liste des utilisateurs à affecter -->
		<?php require( USERS_VIEW . '/list-user-to-assign.php' ); ?>
	</div>
</section>
