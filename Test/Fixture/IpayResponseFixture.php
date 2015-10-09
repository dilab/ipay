<?php
/**
 * IpayResponseFixture
 *
 */
class IpayResponseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'merchant_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => 'The Merchant Code provided by iPay88 and use to uniquely identify the Merchant.', 'charset' => 'latin1'),
		'payment_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'ref_no' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => 'Unique merchant transaction number / Order ID', 'charset' => 'latin1'),
		'amount' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'comment' => 'Payment amount with two decimals and thousand symbols. Example: 1,278.99', 'charset' => 'latin1'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'remark' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'comment' => 'Merchant remarks', 'charset' => 'latin1'),
		'trans_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'comment' => 'iPay88 OPSG Transaction ID', 'charset' => 'latin1'),
		'auth_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => 'Bank�s approval code', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'comment' => 'Payment status �1� � Success �0� � Fail', 'charset' => 'latin1'),
		'err_desc' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'comment' => 'Payment status description', 'charset' => 'latin1'),
		'signature' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'comment' => 'SHA1 signature', 'charset' => 'latin1'), 'payment_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'is_success' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'requery_response' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'comment' => 'Bank�s approval code', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
	);

}
