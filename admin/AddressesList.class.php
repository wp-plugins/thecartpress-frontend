<?php
/**
 * This file is part of TheCartPress.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once( WP_PLUGIN_DIR . '/thecartpress/daos/Addresses.class.php' );

class TCPAddressesList {

	function show( $echo = true ) {
		if ( is_admin() ) $admin_path = TCP_ADMIN_PATH . 'AddressEdit.php';
		else $admin_path = get_permalink( get_option( 'tcp_address_edit_page_id' ) );
		if ( ! is_user_logged_in() ) : ob_start(); ?>
			<p><?php _e( 'You need to login to see your address.', 'tcp' ); ?></p>
			<?php tcp_login_form( array( 'echo' => true ) ); ?>
		<?php return ob_get_clean();
		endif;
		global $current_user;
		get_currentuserinfo();
		if ( isset( $_REQUEST['tcp_delete_address'] ) ) {
			$address_id = isset( $_REQUEST['address_id'] ) ? $_REQUEST['address_id'] : 0;
			if ( $address_id > 0 &&	Addresses::isOwner( $address_id, $current_user->ID ) ) {
				Addresses::delete( $address_id ); ?>
<div class="alert alert-success">
	<?php _e( 'Address deleted', 'tcp' ); ?>
</div><?php
			}
		}
		$addresses = Addresses::getCustomerAddresses( $current_user->ID );
		ob_start(); ?>

<div class="tcpf">

<?php if ( is_admin() ) : ?><h2><?php _e( 'List of addresses', 'tcp' ); ?></h2><?php endif; ?>

<button type="button" onclick="window.location='<?php echo $admin_path; ?>';" class="btn btn-primary"><?php _e( 'Create new address', 'tcp' ); ?></button>

<table class="table table-striped table-hover">
<thead>
<tr>
	<th scope="col" class="manage-column"><?php _e( 'Address', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Name', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Street', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Default', 'tcp' ); ?></th>
	<th scope="col" class="manage-column" style="width: 20%;">&nbsp;</th>
</tr>
</thead>

<tfoot>
<tr>
	<th scope="col" class="manage-column"><?php _e( 'Address', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Name', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Street', 'tcp' ); ?></th>
	<th scope="col" class="manage-column"><?php _e( 'Default', 'tcp' ); ?></th>
	<th scope="col" class="manage-column" style="width: 20%;">&nbsp;</th>
</tr>
</tfoot>
<tbody>

<?php if ( count( $addresses ) == 0 ) : ?>
	<tr><td colspan="5"><?php _e( 'The list of addresses is empty', 'tcp' ); ?></td></tr>
<?php else :
	foreach( $addresses as $address ) : ?>
	<tr>
		<td><?php echo $address->address_id; if ( !empty( $address->name ) ) echo stripslashes( $address->name ); ?></td>
		<td><?php echo stripslashes( $address->firstname ), ' ', stripslashes( $address->lastname ); ?></td>
		<td><?php echo stripslashes( $address->street ), ' ', stripslashes( $address->city ), ' (', stripslashes( $address->region ), ')'; ?></td>
		<?php if ( $address->default_shipping == 'Y' ) {
			$default = __( 'Shipping', 'tcp' );
		} else {
			$default = '';
		}
		if ( $address->default_billing == 'Y' ) {
			$default .= ' ' . __( 'Billing', 'tcp' );
		} ?>
		<td><?php echo $default; ?></td>
		<td style="width: 20%;">
			<div class="btn-group btn-group-xs">
				<button type="button" onclick="window.location='<?php echo add_query_arg( 'address_id', $address->address_id, $admin_path ); ?>'" class="btn btn-success" title="<?php _e( 'edit', 'tcp' ); ?>"><span class="glyphicon glyphicon-pencil"></span> <span class="sr-only"><?php _e( 'edit', 'tcp' ); ?></span></button>
				<button type="button" onclick="jQuery('.delete_address').hide();jQuery('#delete_<?php echo $address->address_id; ?>').show();return false;" class="btn btn-danger" title="<?php _e( 'delete', 'tcp' ); ?>"><span class="glyphicon glyphicon-trash"></span> <span class="sr-only"><?php _e( 'delete', 'tcp' ); ?></span></button>
			</div>
			<div id="delete_<?php echo $address->address_id; ?>" class="delete_address" style="display:none; border: 1px dotted orange; padding: 2px">
				<form method="post" name="frm_delete_<?php echo $address->address_id; ?>">
					<input type="hidden" name="address_id" value="<?php echo $address->address_id; ?>" />
					<input type="hidden" name="tcp_delete_address" value="y" />
					<p><?php _e( 'Do you really want to delete this address?', 'tcp' ); ?></p>
					<div class="btn-group btn-group-xs">
						<button type="button" onclick="document.frm_delete_<?php echo $address->address_id; ?>.submit();" class="btn btn-danger" title="<?php _e( 'Yes' , 'tcp' ); ?>"><span class="glyphicon glyphicon-ok"></span></button>
						<button type="button" onclick="jQuery('#delete_<?php echo $address->address_id; ?>').hide();return false;" class="btn btn-success" title="<?php _e( 'No, I don\'t' , 'tcp' ); ?>"><span class="glyphicon glyphicon-remove"></span></button>
					</div>
				</form>
			</div>
		</td>
	</tr>
	<?php endforeach;
endif; ?>
</tbody>
</table>
</div> <!-- end wrap --><?php
		$out = ob_get_clean();
		if ( $echo ) echo $out;
		return $out;
	}
}