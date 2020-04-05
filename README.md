# Geodata

## Installation

`composer require aspross/geodata`

## Documentation

### Scripts

```php
<?php
use \AnthonySpross\Assets\GeoData; 

getCoordinates(string $address)

getDistance(string $location1Latitude, string $location1Longitude, string $location2Latitude, string $location2Longitude, string $unit = 'K')

``` 

## Usage

```php
<?php
// get geodata coordinates for an address
$coordinates = GeoData::getCoordinates("Werner-Heisenberg-Allee 25, 80939 München, Deutschland");

// get distance between two geodata positions
$distance = GeoData::getDistance("48.8588377", "2.2770206", "52.5067614", "13.2846508", "K");

/**
 * unit specification | $unit
 * 
 * K -> Kilometer
 * M -> Miles
 * N -> Nautical miles
**/
```