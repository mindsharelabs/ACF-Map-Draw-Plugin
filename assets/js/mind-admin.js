/**
* Custom Mapbox Polygon JS
*/

(function($) {
  mapboxgl.accessToken = acf.data.mapbox_api;
  var fields = acf.findFields({
    type: 'mapbox_polygon'
  });
  
  $(document).ready(function(){
    console.log('aworking');
    for(var i = 0; i < fields.length; i++) {
      var map = new mapboxgl.Map({
        container: 'mapPolygon_' + fields[i].dataset.key,
        style: 'mapbox://styles/thejameswilliam/ck8vw9xzg1ivp1io70ijqq67q',
        center: [-105.960045, 35.676821],
        zoom: 12
      });
      var field = fields[i].dataset.name;

      map.on('load', function() {
        map.resize();
        var draw = new MapboxDraw({
          displayControlsDefault: false,
          controls: {
            polygon: true,
            trash: true,
            point: true,
            line_string: true
          }
        });
        map.addControl(draw);

        $.ajax({
          url : acf.data.ajax_url,
          type : 'post',
          data : {
            action : 'mindpolygon_getgeo',
            postid : acf.data.post_id,
            meta_key : field
          },
          success: function(response) {
            draw.add(response.data);
          },
          error: function (response) {
            console.log('An error occurred.');
            console.log(response);
          },
        });

        map.on('draw.create', turoUpdateGeo);
        map.on('draw.delete', turoUpdateGeo);
        map.on('draw.update', turoUpdateGeo);

        function turoUpdateGeo() {
          var data = draw.getAll();
          console.log(field);
          $.ajax({
            url : acf.data.ajax_url,
            type : 'post',
            data : {
              action : 'mindpolygon_updategeo',
              postid : acf.data.post_id,
              data : data,
              field : field
            },
            success: function(response) {
              // console.log(response);
            },
            error: function (response) {
              console.log('An error occurred.');
              console.log(response);
            },
          });
        }

      })

    }
  })





})(jQuery);
