<?php
/**
 * Assets base component.
 *
 * This file handles several geodata based functions
 *
 * @license GPL-2.0+
 */

namespace AnthonySpross\Assets;

class GeoData
{
    // Geodata address
    protected $address;

    // Geodata distance
    protected $distance;

    // Geodata coordinates
    protected $data = [];

    /**
     * Get geodata from given address.
     *
     * @return array Geodata coordinates
     **/
    public static function getCoordinates(string $address): array
    {
        $address = rawurlencode( $address );
        $data = get_transient( 'geocode_' . $address );

        if( empty( $data ) ) {
            $url  = 'http://nominatim.openstreetmap.org/?format=json&addressdetails=1&q=' . $address . '&format=json&limit=1';
            $json = wp_remote_get( $url );

            if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {
                $body = wp_remote_retrieve_body( $json );
                $json = json_decode( $body, true );
            }

            $data['lat']  = $json[0]['lat'];
            $data['long'] = $json[0]['lon'];
            
            set_transient( 'geocode_' . $address, $data, DAY_IN_SECONDS * 90 );
        }

        return $data;
    }

    /**
     * Return Distance between two locations.
     *
     * @return array Geodata coordinates
     **/
    public static function getDistance(string $location1Latitude, string $location1Longitude, string $location2Latitude, string $location2Longitude, string $unit = 'K'): string 
    {   
        // both with the same location
        if (($location1Latitude == $location2Latitude) && ($location1Longitude == $location2Longitude)) {
            $distance = 0;
        }
        else {
            $theta = $location1Longitude - $location2Longitude;
            $distance = sin(deg2rad($location1Latitude)) * sin(deg2rad($location2Latitude)) +  cos(deg2rad($location1Latitude)) * cos(deg2rad($location2Latitude)) * cos(deg2rad($theta));
            $distance = acos($distance);
            $distance = rad2deg($distance);
            $miles = $distance * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                $distance = ($miles * 1.609344);
            } else if ($unit == "N") {
                $distance = ($miles * 0.8684);
            } else {
                $distance = $miles;
            }
        }

        return $distance;
    }

}