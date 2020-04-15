<?php


class mindMapboxPolygonAjax {
  private $options = '';
  private $token = '';

  function __construct() {

    add_action( 'wp_ajax_' . MINDPOLYGON_PREPEND . 'updategeo', array( $this, 'updategeo' ) );

    add_action( 'wp_ajax_' . MINDPOLYGON_PREPEND . 'getgeo', array( $this, 'getgeo' ) );

    // add_action( 'wp_ajax_nopriv_' . MINDRETURNS_PREPEND . 'get_event_meta_html', array( $this, 'get_event_meta_html' ) );
    // add_action( 'wp_ajax_' . MINDRETURNS_PREPEND . 'get_event_meta_html', array( $this, 'get_event_meta_html' ) );

  }
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }


  public function updategeo() {
    if($_POST['action'] == MINDPOLYGON_PREPEND . 'updategeo'){
      $features = $_POST['data']['features'];
      $data = $_POST['data'];
      if($features) :
        foreach ($features as $key => $feature) :
          $feature['properties'] = array('' => '');
          $features[$key] = $feature;
        endforeach;
      endif;
      $data['features'] = $features;

      $update = update_post_meta($_POST['postid'], $_POST['field'], $data);

      return json_encode($update, JSON_FORCE_OBJECT);
    }
    wp_send_json_error();
  }

  public function getgeo() {
    if($_POST['action'] == MINDPOLYGON_PREPEND . 'getgeo'){
      $post_meta = get_post_meta($_POST['postid'], $_POST['meta_key'], true);
      if($post_meta) :
        $features = $post_meta['features'];


        //This is hacky, but we have to loop through all the coordinates and turn them into floatval, they cannot be strings.
        foreach ($features as $f => $feature) :
          if($feature['geometry']['type'] == 'Polygon') :
            foreach ($feature['geometry']['coordinates'] as $c => $coordinates) :
              foreach ($coordinates as $a => $array) :
                $features[$f]['geometry']['coordinates'][$c][$a] = array(
                  floatval($array[0]), floatval($array[1])
                );
              endforeach;
            endforeach;

          elseif($feature['geometry']['type'] == 'Point') :
            $features[$f]['geometry']['coordinates'] = array(
              floatval($features[$f]['geometry']['coordinates'][0]),
              floatval($features[$f]['geometry']['coordinates'][1])
            );

          elseif($feature['geometry']['type'] == 'LineString') :
            foreach ($feature['geometry']['coordinates'] as $c => $coordinate) :
              $features[$f]['geometry']['coordinates'][$c] = array(
                floatval($coordinate[0]), floatval($coordinate[1])
              );
            endforeach;
          endif;


        endforeach;
        $post_meta['features'] = $features;

      endif;
      wp_send_json_success($post_meta);
    }
    wp_send_json_error();
  }

}



new mindMapboxPolygonAjax();
