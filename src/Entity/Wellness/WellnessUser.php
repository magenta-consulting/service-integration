<?php
namespace App\Entity\Wellness;


class WellnessUser
{
    public static function generateTimestampBasedCode(\DateTime $date = null) {
        if($date === null) {
            $timestamp = base_convert((integer) date_timestamp_get(new \DateTime()), 10, 36);
        } else {
            $timestamp = base_convert($date->getTimestamp(), 10, 36);
        }
        for($i = 0; $i < 8 - strlen($timestamp);) {
            $timestamp = '0' . $timestamp;
        }

        $tsStr = substr(chunk_split($timestamp, 4, "-"), 0, - 1);

        return strtoupper($tsStr);
    }

    public static function generate4DigitCode($code = null) {
        if(empty($code)) {
            $code = base_convert(rand(0, 1679615), 10, 36);
        }
        for($i = 0; $i < 4 - strlen($code);) {
            $code = '0' . $code;
        }

        return strtoupper($code);
    }

    public static function generateXDigitCode($code = null, $x) {
        if(empty($code)) {
            $maxBase36 = '';
            for($i = 0; $i < $x; $i ++) {
                $maxBase36 .= 'z';
            }

            $maxBase10 = base_convert($maxBase36, 36, 10);

            $code = base_convert(rand(0, $maxBase10), 10, 36);
        }

        for($i = 0; $i < $x - strlen($code);) {
            $code = '0' . $code;
        }

        return strtoupper($code);
    }

}