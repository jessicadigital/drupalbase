<?php
namespace Drupal\drupalbase\Twig;

class TwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drupalbase';
  }

  public function getFilters() {
    return array('slugify' => new \Twig_Filter_Method($this, 'slugify'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('base_root', array($this, 'base_root'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('boatinfo', array($this, 'boatinfo'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('display_menu', array($this, 'display_menu'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('duration', array($this, 'duration'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('embed_file', array($this, 'embed_file'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('imgurl', array($this, 'imgurl'), array(
          'needs_environment' => TRUE,
          'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('is_ajax', array($this, 'is_ajax'), array(
          'needs_environment' => TRUE,
          'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('latest_article', array($this, 'latest_article'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('latest_instagram', array($this, 'latest_instagram'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('latest_tweet', array($this, 'latest_tweet'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('node_content_link', array($this, 'node_content_link'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('node_image', array($this, 'node_image'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('node_link', array($this, 'node_link'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('node_short_name', array($this, 'node_short_name'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('node_title', array($this, 'node_title'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('noextension', array($this, 'noextension'), array(
          'is_safe' => array('html'),
          'needs_environment' => TRUE,
          'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('pagetitle', array($this, 'pagetitle'), array(
          'needs_environment' => TRUE,
          'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('pageurl', array($this, 'pageurl'), array(
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('place_block', array($this, 'place_block'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('place_form', array($this, 'place_form'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('place_node', array($this, 'place_node'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('place_package', array($this, 'place_package'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('static_block', array($this, 'static_block'), array(
        'is_safe' => array('html'),
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('themeurl', array($this, 'themeurl'), array(
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
      new \Twig_SimpleFunction('unescape', array($this, 'unescape'), array(
        'needs_environment' => TRUE,
        'needs_context' => TRUE,
      )),
    );
  }

  public function base_root(\Twig_Environment $env, array $context) {
      global $base_root;
      return $base_root;
  }

  public function boatinfo(\Twig_Environment $env, array $context, $nid) {
      $node = node_load($nid)->toArray();
      if (empty($node)) {
          return '';
      }
      else {
          $floorplan = empty($node['field_boat_floorplan'][0]['target_id']) ? false : \Drupal\file\Entity\File::load($node['field_boat_floorplan'][0]['target_id']);
          if (!empty($floorplan)) {
              $floorplan = $floorplan->url();
          }
          $icon = empty($node['field_boat_icon'][0]['target_id']) ? false : \Drupal\file\Entity\File::load($node['field_boat_icon'][0]['target_id']);
          if (!empty($icon)) {
              $icon = $icon->url();
          }
          $boat = array(
              'disabledaccess' => $node['field_boat_disabledaccess'][0]['value'],
              'floorplan' => $floorplan,
              'image' => $this->imgurl($env, $context, $node['field_boat_image'][0]['target_id'], 'http://placehold.it/800x300'),
              'rooms' => array(),
              'snippet' => $node['field_boat_snippet'][0]['value'],
              'title' => $node['title'][0]['value']
          );
          foreach ($node['field_boat_room'] as $r) {
              $room = node_load($r['target_id'])->toArray();

              if (!empty($room)) {
                  $boat_room_data = array(
                      'boat' => [
                          'floorplan' => $floorplan,
                          'icon' => $icon,
                          'id' => $nid,
                          'name' => $node['title'][0]['value']
                      ],
                      'capacity_1' => empty($room['field_room_capacity_1'])?false:$room['field_room_capacity_1'][0]['value'],
                      'capacity_2' => empty($room['field_room_capaci'])?false:$room['field_room_capaci'][0]['value'],
                      'copy' => empty($room['field_room_copy'])?false:$room['field_room_copy'][0]['value'],
                      'display_style' => empty($room['field_display_style'][0]['value'])?false:$room['field_display_style'][0]['value'],
                      'image' => empty($room['field_room_image'])?false:$this->imgurl($env, $context, $room['field_room_image'][0]['target_id'], ''),
                      'label_1' => empty($room['field_room_label_1'])?false:$room['field_room_label_1'][0]['value'],
                      'label_2' => empty($room['field_room_label_2'])?false:$room['field_room_label_2'][0]['value'],
                      'name' => empty($room['field_room_name'])?false:$room['field_room_name'][0]['value']
                  );

                  $boat_room_data['all'] = json_encode($boat_room_data);

                  $boat['rooms'][] = $boat_room_data;
              }
          }
          return $this->static_block($env, $context, 'boatinfo', array('boat' => $boat));
      }
  }

  /**
   * Provides display_menu function for page layouts.
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function display_menu(\Twig_Environment $env, array $context, $menu_name) {
    $menu_tree = \Drupal::menuTree();

      // Build the typical default set of menu tree parameters.
      $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);

      // Load the tree based on this set of parameters.
      $tree = $menu_tree->load($menu_name, $parameters);

      // Transform the tree using the manipulators you want.
      $manipulators = array(
        // Only show links that are accessible for the current user.
        array('callable' => 'menu.default_tree_manipulators:checkAccess'),
        // Use the default sorting of menu links.
        array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
      );
      $tree = $menu_tree->transform($tree, $manipulators);

      // Finally, build a renderable array from the transformed tree.
      $menu = $menu_tree->build($tree);

      return  array('#markup' => drupal_render($menu));
  }

  public function duration(\Twig_Environment $env, array $context, $hours) {
      $hour = floor($hours);
      if ($hour == $hours) {
          return $hour.'h';
      }
      else {
          return $hour.'h '.(($hours-$hour)*60).'m';
      }
  }

  public function embed_file(\Twig_Environment $env, array $context, $url) {
      return file_get_contents($url);
  }

  public function imgurl(\Twig_Environment $env, array $context, $id, $fallback) {
      $file = \Drupal\file\Entity\File::load($id);
      if (empty($file)) {
          return $fallback;
      }
      else{
          return $file->url();
      }
  }

  public function is_ajax(\Twig_Environment $env, array $context) {
      return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
  }

  public function latest_article(\Twig_Environment $env, array $context, $current_id) {
    $query = \Drupal::entityQuery('node');
    $result = $query->condition('nid', $current_id, '<>')
      ->condition('type', 'article')
      ->sort('created', 'DESC')
      ->range(0, 1)
      ->execute();

    if (empty($result)) {
        return '';
    }
    else {
        $entity = entity_load('node', reset($result));
        if (empty($entity)) {
            return '';
        }
        else {
            $article = $entity->toArray();
            $article['nurl'][0]['value'] = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $article['nid'][0]['value']], ['absolute' => true])->toString();
            return $this->static_block($env, [], 'latest_article', array('article' => $article));
        }
    }
  }

  public function latest_instagram(\Twig_Environment $env, array $context) {
      $filepath = DRUPAL_ROOT.'/'.\Drupal\Core\StreamWrapper\PrivateStream::basePath().'/social.instagram.json';
      if (file_exists($filepath)) {
          $filecontents = file_get_contents($filepath);
          if (empty($filecontents)) {
              return '';
          }
          else {
              $instagrams = json_decode($filecontents, TRUE);
              foreach ($instagrams as $instagram) {
                  if ($instagram['user']['handle'] === 'reckless_hq') {
                      return $this->static_block($env, [], 'latest_instagram', array('instagram' => $instagram));
                  }
              }
              return '';
          }
      }
      else {
          return '';
      }
  }

  public function latest_tweet(\Twig_Environment $env, array $context) {
      $filepath = DRUPAL_ROOT.'/'.\Drupal\Core\StreamWrapper\PrivateStream::basePath().'/social.twitter.json';
      if (file_exists($filepath)) {
          $filecontents = file_get_contents($filepath);
          if (empty($filecontents)) {
              return '';
          }
          else {
              $tweets = json_decode($filecontents, TRUE);
              foreach ($tweets as $tweet) {
                  if ($tweet['user']['handle'] === 'BateauxLondon') {
                      return $this->static_block($env, [], 'latest_tweet', array('tweet' => $tweet));
                  }
              }
              return '';
          }
      }
      else {
          return '';
      }
  }

  public function node_content_link(\Twig_Environment $env, array $context, $nid) {
      $node = node_load($nid);
      return (empty($node) || empty($node->field_content_1_link[0]->uri)) ? '' : \Drupal\Core\Url::fromUri($node->field_content_1_link[0]->uri)->toString();
  }

  public function node_image(\Twig_Environment $env, array $context, $nid) {
      $node = node_load($nid);
      return (empty($node) || empty($node->field_feature_image[0]->entity)) ? '' : $node->field_feature_image[0]->entity->url();
  }

  public function node_link(\Twig_Environment $env, array $context, $nid) {
      return \Drupal\Core\Url::fromRoute('entity.node.canonical', array('node' => $nid));
  }

  public function node_short_name(\Twig_Environment $env, array $context, $nid) {
      $node = node_load($nid);
      return empty($node) ? '' : $node->field_short_name[0]->value;
  }

  public function node_title(\Twig_Environment $env, array $context, $nid) {
      $node = node_load($nid);
      return empty($node) ? '' : $node->title[0]->value;
  }

  public function noextension(\Twig_Environment $env, array $context, $filename) {
      return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
  }

  public function pagetitle(\Twig_Environment $env, array $context) {
      $request = \Drupal::request();
      if ($route = $request->attributes->get(\Symfony\Cmf\Component\Routing\RouteObjectInterface::ROUTE_OBJECT)) {
        return \Drupal::service('title_resolver')->getTitle($request, $route);
      }
  }

  public function pageurl(\Twig_Environment $env, array $context) {
      return \Drupal\Core\Url::fromRoute('<current>', [], ['absolute' => true]);
  }

  /**
   * Places a content block
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function place_block(\Twig_Environment $env, array $context, $block_name) {
    $block_manager = \Drupal::service('plugin.manager.block');
    $config = [];
    $plugin_block = $block_manager->createInstance($block_name, $config);
    $render = $plugin_block->build();
    return $render;
  }

  /**
   * Places a form
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function place_form(\Twig_Environment $env, array $context, $form_name) {
      return  \Drupal::formBuilder()->getForm($form_name);
  }

  public function place_node(\Twig_Environment $env, array $context, $node_id, $node_view = 'full') {
      $node = entity_load('node', $node_id);
      if (empty($node)) {
          return '';
      }
      else {
          return node_view($node, $node_view);
      }
  }

  public function place_package(\Twig_Environment $env, array $context, $nid) {
      $node = entity_load('node', $nid);
      if (empty($node)) {
          return '';
      }
      else {
          return node_view($node, 'slimcruisepackage');
      }
  }

  /**
   * Slugifies a string.
   * Inspiration from https://gist.github.com/boboldehampsink/7354431
   */
  public function slugify($slug) {
    // Remove HTML tags
    $slug = preg_replace('/<(.*?)>/u', '', $slug);

    // Remove inner-word punctuation.
    $slug = preg_replace('/[\'"‘’“”]/u', '', $slug);

    // Make it lowercase
    $slug = mb_strtolower($slug, 'UTF-8');

    // Get the "words".  Split on anything that is not a unicode letter or number.
    // Periods are OK too.
    preg_match_all('/[\p{L}\p{N}\.]+/u', $slug, $words);
    $slug = implode('-', array_filter($words[0]));

    return $slug;
  }

  /**
   * Loads a static template block.
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function static_block(\Twig_Environment $env, array $context, $static_block_name, $variables = array()) {
      return [
          [
              '#markup' => twig_render_template(\Drupal::theme()->getActiveTheme()->getPath().'/templates/static/'.$static_block_name.'.html.twig', array_merge($context, $variables, ['theme_hook_original' => '']))
          ]
      ];
  }

  /**
   * Creates a theme URL
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function themeurl(\Twig_Environment $env, array $context, $theme_asset) {
      return '/'.\Drupal::theme()->getActiveTheme()->getPath().$theme_asset;
  }

  public function unescape(\Twig_Environment $env, array $context, $html) {
      return html_entity_decode($html);
  }

}
