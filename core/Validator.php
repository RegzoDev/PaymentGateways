<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 09.06.15
 * Time: 17:54
 */

namespace core;


class Validator {

    const STRING_PARAM_TYPE = 'string';
    const INT_PARAM_TYPE = 'int';
    const ARRAY_PARAM_TYPE = 'array';
    const DATE_PARAM_TYPE = 'date';

    public function validate($type, $value, $name = '') {
        $valid = true;
        switch($type) {
            case self::STRING_PARAM_TYPE:
                if(strlen($value) < 2 || strlen($value) > 200) {
                    throw new \Exception('Invalid string parameter: '. $name);
                }
                break;

            case self::INT_PARAM_TYPE:
                if($value < 0) {
                    throw new \Exception('Invalid int parameter: '. $name);
                }
                break;

            case self::DATE_PARAM_TYPE:
                if($value < 0 || ($value > 12 && $value < date('Y'))) {
                    throw new \Exception('Invalid date parameter: '. $name);
                }
                break;

            default:

        }

        if(is_array($type) && !in_array($value, $type)) {
            throw new \Exception('Invalid array parameter: '. $name .'. Must be '. json_encode($type));
        }

        return $valid;
    }
} 