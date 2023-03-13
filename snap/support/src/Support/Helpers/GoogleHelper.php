<?php

namespace Snap\Support\Helpers;

class GoogleHelper
{
    /**
     * This will query Google to get the geo location information from a given address.
     *
     * @param $address
     * @return array
     */
    public static function geoLocate($address)
    {
        if (is_array($address)) {
            $address = implode(', ', $address);
        }

        $url = config('snap.google.geocode_url').'?key='.config('snap.google.api_key').'&address='.rawurlencode($address);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($curl), false);

        if ($result->status == 'OK') {
            $res = $result->results[0];

            return [
                'latitude'  => $res->geometry->location->lat,
                'longitude' => $res->geometry->location->lng,
            ];
        } else {

            return [
                'latitude'  => 0,
                'longitude' => 0,
            ];
        }
    }
}
