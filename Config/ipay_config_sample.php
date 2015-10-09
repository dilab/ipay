<?php
Configure::write('Ipay', array(
	'merchantKey'      => '123', // merchant Key - Change to your merchantKey
	'merchantCode'     => '123', // merchant Code - Change to your merchantCode
	'requestUrl'       => 'https://www.mobile88.com/ePayment/entry.asp',  // Base URL for request - Don't edit
	'requeryUrl'       => 'https://www.mobile88.com/epayment/enquiry.asp' // Base URL for re-query - Don't edit
));

