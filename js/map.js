 document.addEventListener('DOMContentLoaded', function () {
    // Assuming the locations variable is available from Twig
    /*let markers = locations.map(location => ({
        name: location.title, // Assuming 'title' is the property you want to use
        coords: [parseFloat(location.latitude), parseFloat(location.longitude)],
        style: { initial: { fill: '#ff6e31' } } // Customize style for each marker
    }));*/
    // Assuming locations variable is available from Twig or fetched dynamically
    let markers = locations.map(location => {
        // Base marker structure
        let marker = {
            name: location.title, // Assuming 'title' is the property for the location name
            coords: [parseFloat(location.latitude), parseFloat(location.longitude)],
        };

        // Apply a specific style only if the location is in Canada
        if (location.country_code === 'CA') {
            marker.style = {
                initial: {
                    fill: '#ff6e31' // Custom color for the Canada marker
                }
            };
        }

        return marker;
    });

    // Set regions based on locations
    let regions = locations.map(location => location.country_code);

    // Initialize the map
    const map = new jsVectorMap({
        selector: "#map",
        map: "world_merc",
        zoomButtons: true,
        regionStyle: {
            selected: { fill: '#ff6e31' },
            hover: { fill: '#ff6e31' },            
        },
        selectedRegions: regions,
        markers: markers,
    });

    // Function to handle highlighting a country
    function highlightCountry(countryCode) {
        map.clearSelectedRegions();
        map.setSelectedRegions([countryCode]);
    }

    // Event listener for accordion item clicks
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function () {
            const country = this.getAttribute('data-country'); // Get the country code
            highlightCountry(country);
        });
    });

    // Highlight the first country on page load (Canada as an example)
    highlightCountry("CA");
});
/*

let markers = [
              { name: 'Canada', coords: [45.5088,-73.5878],  // Montreal coords
                style: {
                      initial: {
                          fill: '#ff6e31'  // Customize style for this marker
                      }
                  } 
              },
              { name: 'Romania', coords: [45.94, 24.97] },
              { name: 'Georgia', coords: [42.32, 43.36] },
              { name: 'Taiwan', coords: [23.7, 120.96] },
              { name: 'China', coords: [35.86, 104.2] },
              { name: 'Peru', coords: [-9.19, -75.02] },
              { name: 'Turkey', coords: [38.96, 35.24] },
              { name: 'South Korea', coords: [35.91, 127.77] },
              { name: 'Vietnam', coords: [14.06, 108.28] },

            ];
    let regions = ['CA','RO','GE','CN','PE','TR','KR','VN'];
    // Initialize the map
    const map = new jsVectorMap({
      selector: "#map",
      map: "world_merc",
      zoomButtons: true,
      regionStyle: {
		  selected: { fill: '#ff6e31' },
		  // Everything in initial property can be overwritten here
		  hover: { fill: '#ff6e31' },
		},
	  selectedRegions: regions,
      markers: markers,
    });

    // Function to handle highlighting a country
    function highlightCountry(countryCode) {
      // Clear all selected regions
      map.clearSelectedRegions()
      map.setSelectedRegions([countryCode]);
      // Add one marker
      //map.addMarkers({ name: countryCode, coords: [61, 105] });
*/
      // Or pass an array of markers
    /*  map.addMarkers([{
        name: 'Russia',
        coords: [61, 105]
      }, {
        name: 'Egypt',
        coords: [26.8206, 30.8025],
        // Add additional style for this particular marker.
        style: {
          initial: {
            fill: 'red' 
          }
        }
      }]);
      */
      
 /*    
    }

    // Event listener for accordion item clicks
    document.querySelectorAll('.accordion-button').forEach(button => {
      button.addEventListener('click', function () {
        
        const country = this.getAttribute('data-country'); // Get the country code
    */
        /*alert(country);*/
 /*       highlightCountry(country);
       
         // map.setBackgroundColor('#f6f6f6')

      });
    });

    // Highlight the first country on page load (Canada as an example)
    highlightCountry("CA");
*/