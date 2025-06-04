<?php
/**
* main page display for vector map of locations
* @locations an array of locations from the config file.
*/
namespace Drupal\custom_vectormap\Controller;

use Drupal\Core\Controller\ControllerBase;

class MapAccordionController extends ControllerBase {

  public function mapContent() {
   $config = \Drupal::config('custom_vectormap.settings');
	
  // Retrieve the saved items from the configuration.
  $locations = $config->get('locations') ?? []; // Use 'items' here to get the correct data.

  // Prepare the locations array for rendering.
  $mapped_locations = [];
  
  foreach ($locations as $location) {
    $mapped_locations[] = [
      'name' => $location['title'] ?? 'Unknown Location', // Using title from the config.
      'address' => $location['body'] ?? 'No Address Provided', // Using body from the config.
      'latitude' => $location['latitude'] ?? '',
      'longitude' => $location['longitude'] ?? '',
      'country' => $location['country'] ?? 'Unknown Country',
      'country_code' => $location['country_code'] ?? '',
    ];
  }
	
	
	
	
	return [
      '#theme' => 'map_accordion_page',
      '#locations' => $mapped_locations,
      '#attached' => [
        'library' => [
          'custom_vectormap/map_accordion_library',
        ],		
      ],
    ];
	
  }
}