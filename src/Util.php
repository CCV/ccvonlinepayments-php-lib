<?php namespace CCVOnlinePayments\Lib;

class Util {

    public static function toFloat(null|string|int|float $number): ?float {
        if($number === null) {
            return null;
        }elseif(is_string($number)) {
            if(is_numeric($number)) {
                return floatval($number);
            }else{
                throw new \DomainException("'$number' is not a valid number'");
            }
        }else{
            return floatval($number);
        }
    }

    public static function floatToString(null|float $number) : ?string {
        if($number === null) {
            return null;
        }else{
            return number_format($number,2,".","");
        }
    }
}
