<?php
/**
 * Assets base component.
 *
 * This file handles several geodata based functions
 *
 * @license GPL-2.0+
 */

namespace AnthonySpross\Assets;

class Asset
{
    // Geodata address
    protected $address;

    // Geodata coordinates
    protected $data = [];

    /**
     * Get geodata from given address.
     *
     * @return string Path to asset file
     **/
    public static function getCoordinatesAsset(string $address): array
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

}