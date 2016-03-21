<?php

/**
 * @file
 * Contains \Drupal\drupalbase\Plugin\Block\OlderNewerBlock.
 * 
 * Inspired by http://www.blinkreaction.com/blog/create-a-simple-nextprevious-navigation-in-drupal-8
 */

namespace Drupal\drupalbase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;


/**
 * Provides a 'Older Newer' block.
 *
 * @Block(
 *   id = "older_newer_block",
 *   admin_label = @Translation("Older Newer Block"),
 *   category = @Translation("Blocks")
 * )
 */
class OlderNewerBlock extends BlockBase {

  // Current node
  private $node;
  
  // Current node type
  private $type;
    
  /**
   * {@inheritdoc}
   */
  public function build() {

    //Get the created time of the current node
    $this->node = \Drupal::request()->attributes->get('node');
    
    $created_time = $this->node->getCreatedTime();
    $link = '';

    $link .= $this->generatePrevious($created_time);
    $link .= $this->generateNext($created_time);

    return array('#markup' => $link);
  }

  /**
   * Lookup the previous node, i.e. youngest node which is still older than the node
   * currently being viewed.
   *
   * @param  string $created_time A unix time stamp
   * @return string               an html link to the previous node
   */
  private function generatePrevious($created_time) {
    return $this->generateNextPrevious('prev', $created_time);
  }

  /**
   * Lookup the next node, i.e. oldest node which is still younger than the node
   * currently being viewed.
   *
   * @param  string $created_time A unix time stamp
   * @return string               an html link to the next node
   */
  private function generateNext($created_time) {
    return $this->generateNextPrevious('next', $created_time);
  }

  /**
   * Lookup the next or previous node
   *
   * @param  string $direction    either 'next' or 'previous'
   * @param  string $created_time a Unix time stamp
   * @return string               an html link to the next or previous node
   */
  private function generateNextPrevious($direction = 'next', $created_time) {

    if ($direction === 'next') {
      $comparison_operator = '>';
      $sort = 'ASC';
      $display_text = t('Newer');
    }
    elseif ($direction === 'prev') {
      $comparison_operator = '<';
      $sort = 'DESC';
      $display_text = t('Older');
    }

    //Lookup 1 node younger (or older) than the current node
    $query = \Drupal::entityQuery('node');
    $next = $query->condition('created', $created_time, $comparison_operator)
      ->condition('type', $this->type)
      ->sort('created', $sort)
      ->range(0, 1)
      ->execute();

    //If this is not the youngest (or oldest) node
    if (!empty($next) && is_array($next)) {
      $next = array_values($next);
      $next = $next[0];

      //Find the alias of the next node
      $next_url = \Drupal::service('path.alias_manager')->getAliasByPath('node/' . $next);

      //Build the URL of the next node
      $next_url = Url::fromUri('base://' . $next_url);

      //Build the HTML for the next node
      return \Drupal::l($display_text, $next_url);
    }
  }
}