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

require_once( ABSPATH . 'wp-content/plugins/thecartpress/daos/Orders.class.php' );
require_once( ABSPATH . 'wp-content/plugins/thecartpress/classes/OrderPage.class.php' );

class TCPOrdersList {
	function show( $echo = true ) {
		global $current_user;
		get_currentuserinfo();
		if ( $current_user->ID == 0 ) : ?>

			<p><?php _e( 'You need to login to see your orders.', 'tcp-fe' ); ?></p>

		<?php return;
		endif;
		if ( isset( $_REQUEST['order_id'] ) ) {
			$order_id = $_REQUEST['order_id'];
			if ( Orders::is_owner( $order_id, $current_user->ID ) ) {
				$back = '<p class="tcp_back"><a href="javascript:history.back();">' . __( 'Return to the list of Orders', 'tcp-fe' ) . '</a></p>';
				$out = OrderPage::show( $order_id, true, false, true, false );
				$out = $back . $out . $back;
				if ( $echo ) {
					echo $out;
					return;
				} else {
					return $out;
				}
			}
		}
		$status = '';
		$orders = Orders::getOrdersEx( 1, 999, $status, $current_user->ID );
		ob_start();
		?>
		<table class="tcp_orders_front_end" cellspacing="0">
		<thead>
		<tr>
			<th scope="col"><?php _e( 'ID', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'Date', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'status', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'Total', 'tcp-fe' );?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th scope="col"><?php _e( 'ID', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'Date', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'status', 'tcp-fe' );?></th>
			<th scope="col"><?php _e( 'Total', 'tcp-fe' );?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach( $orders as $order ) :
			$url = add_query_arg( 'order_id', $order->order_id, get_permalink() ); ?>
		<tr class="tcp_first_line">
			<td class="tcp_order_id"><a href="<?php echo $url; ?>"><?php echo $order->order_id; ?></a></td>
			<td class="tcp_created_at"><?php echo $order->created_at; ?></td>
			<td class="tcp_status_<?php echo strtolower( $order->status ); ?>"><?php echo $order->status; ?></td>
			<td><?php $total = - $order->discount_amount;
				$total = OrdersCosts::getTotalCost( $order->order_id, $total );
				echo tcp_format_the_price( OrdersDetails::getTotal( $order->order_id, $total ) ); ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
		<?php $out = ob_get_clean();
		if ( $echo ) echo $out;
		else return $out;
	}
}
?>
