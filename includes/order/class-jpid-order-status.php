<?php

/**
 * List all available order status.
 *
 * This class means to simulate enum data type for order status.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes/order
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JPID_Order_Status {

  /**
	 * @since    1.0.0
	 * @var      string    Order pending status.
	 */
  const PENDING = 'pending';

  /**
	 * @since    1.0.0
	 * @var      string    Order processing status.
	 */
  const PROCESSING = 'processing';

  /**
	 * @since    1.0.0
	 * @var      string    Order ready status.
	 */
  const READY = 'ready';

  /**
	 * @since    1.0.0
	 * @var      string    Order completed status.
	 */
  const COMPLETED = 'completed';

  /**
	 * @since    1.0.0
	 * @var      string    Order on-hold status.
	 */
  const ONHOLD = 'on-hold';

  /**
	 * @since    1.0.0
	 * @var      string    Order cancelled status.
	 */
  const CANCELLED = 'cancelled';

  /**
	 * @since    1.0.0
	 * @var      string    Order refunded status.
	 */
  const REFUNDED = 'refunded';

}
