<?php
/**
 * User: Yaxiong
 */

/**
 * generate random numbers, ten digits
 * @param int $how_many how many numbers you wanna generate
 * @return array
 */
function generate_random_number($how_many)
{
    $result = array();

    for($i=0; $i<$how_many; $i++)
    {
        $code = mt_rand(1000000000, 9999999999);
        $result[] = $code;
    }
    return $result;
}

/**
 * replace null value with empty string in array
 * @param  array
 * @param flag  true means if the value is an array, it will replace null recursively
 * @return array
 */
function replace_null($array, $flag = true)
{
    foreach ($array as $key => $value) {
        if (is_null($value)) {
            $array[$key] = "";
        } else if ($flag) {
            if (is_array($value))
                $array[$key] = replace_null($value, $flag);
        }
    }
    return $array;
}


/**
 * validate Chinese car plate
 * @param $plate
 * @return bool
 */
function plate_check($plate)//[A-Z]
{
    if (preg_match("/[\x80-\xff]{3}[A-Z]{1}[a-z0-9]{5}/A",$plate) and (strlen($plate)==9)) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * Caculate distance between two coordinates
 * lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)
 * lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)
 * unit = the unit you desire for results
 * where: 'M' is statute miles (default)
 * 'K' is kilometers
 * 'N' is nautical miles
 */
function cdistance($lat1, $lon1, $lat2, $lon2, $unit)
{

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return ($miles * 1.609344);
    } else {
        if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}


/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013 Ben Ramsey <http://benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 *
 * Returns the values from a single column of the input array, identified by
 * the $columnKey.
 *
 * Optionally, you may provide an $indexKey to index the values in the returned
 * array by the values from the $indexKey column in the input array.
 *
 * @param array $input A multi-dimensional array (record set) from which to pull
 *                     a column of values.
 * @param mixed $columnKey The column of values to return. This value may be the
 *                         integer key of the column you wish to retrieve, or it
 *                         may be the string key name for an associative array.
 * @param mixed $indexKey (Optional.) The column to use as the index/keys for
 *                        the returned array. This value may be the integer key
 *                        of the column, or it may be the string key name.
 * @return array
 */
function array_column($input = null, $columnKey = null, $indexKey = null)
{
    // Using func_get_args() in order to check for proper number of
    // parameters and trigger errors exactly as the built-in array_column()
    // does in PHP 5.5.
    $argc = func_num_args();
    $params = func_get_args();
    if ($argc < 2) {
        trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
        return null;
    }
    if (!is_array($params[0])) {
        trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
        return null;
    }
    if (!is_int($params[1])
        && !is_float($params[1])
        && !is_string($params[1])
        && $params[1] !== null
        && !(is_object($params[1]) && method_exists($params[1], '__toString'))
    ) {
        trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
        return false;
    }
    if (isset($params[2])
        && !is_int($params[2])
        && !is_float($params[2])
        && !is_string($params[2])
        && !(is_object($params[2]) && method_exists($params[2], '__toString'))
    ) {
        trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
        return false;
    }
    $paramsInput = $params[0];
    $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
    $paramsIndexKey = null;
    if (isset($params[2])) {
        if (is_float($params[2]) || is_int($params[2])) {
            $paramsIndexKey = (int) $params[2];
        } else {
            $paramsIndexKey = (string) $params[2];
        }
    }
    $resultArray = array();
    foreach ($paramsInput as $row) {
        $key = $value = null;
        $keySet = $valueSet = false;
        if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
            $keySet = true;
            $key = (string) $row[$paramsIndexKey];
        }
        if ($paramsColumnKey === null) {
            $valueSet = true;
            $value = $row;
        } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
            $valueSet = true;
            $value = $row[$paramsColumnKey];
        }
        if ($valueSet) {
            if ($keySet) {
                $resultArray[$key] = $value;
            } else {
                $resultArray[] = $value;
            }
        }
    }
    return $resultArray;
}