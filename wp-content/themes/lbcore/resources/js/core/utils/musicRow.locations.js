(function() {
  var getLocations = new Promise(function(resolve, reject) {
    var req = new XMLHttpRequest();
    req.open("GET", "/wp-admin/admin-ajax.php?action=ajax_get_locations", true);
    req.setRequestHeader("Content-Type", "charset=UTF-8");

    req.onload = function() {
      if (req.status >= 200 && req.status < 300) {
        resolve(req.response);
      } else {
        reject(req.statusText);
      }
    }

    req.onerror = function() {
      reject(req.statusText);
    }

    req.send()
  })
  
  getLocations.then(function(response) {
    for( const [id, data ] of Object.entries(JSON.parse(response))) {
      var storeKey = 'location-'+id

      var remove = new Promise(function(resolve) {
        if (sessionStorage.getItem(storeKey)) {
          sessionStorage.removeItem(storeKey)
        }
        resolve(true)
      })
      
      remove.then(sessionStorage.setItem(storeKey, JSON.stringify(data)))
    }
  })
}())