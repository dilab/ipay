<?php

class IpaySignatureGenerator
{

    public static function responseSignature($merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status)
    {
        $amount = str_replace(array(',', '.'), '', $amount);

        $fullStringToHash = implode('', array($merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status));

        return self::signature($fullStringToHash);
    }

    public static function requestSignature($merchantKey, $merchantCode, $refNo, $amount, $currency)
    {
        $amount = str_replace(array(',', '.'), '', $amount);

        $fullStringToHash = implode('', array($merchantKey, $merchantCode, $refNo, $amount, $currency));

        return self::signature($fullStringToHash);
    }

    private static function signature($source)
    {
        return base64_encode(self::hex2bin(sha1($source)));
    }

    private static function hex2bin($hexSource)
    {
        $bin = '';
        for ($i = 0; $i < strlen($hexSource); $i = $i + 2) {
            $bin .= chr(hexdec(substr($hexSource, $i, 2)));
        }
        return $bin;
    }

}