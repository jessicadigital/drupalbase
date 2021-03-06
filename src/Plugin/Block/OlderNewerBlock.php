<?php

/**
 * @file
 * Contains \Drupal\drupalbase\Plugin\Block\OlderNewerBlock.
 *
 * Inspired by http://www.blinkreaction.com/blog/create-a-simple-nextprevious-navigation-in-drupal-8
 */

namespace Drupal\drupalbase\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;


/**
 * Provides a 'Older Newer' block.
 *
 * @Block(
 *   id = "oldernewerblock",
 *   admin_label = @Translation("Older Newer Block"),
 *   category = @Translation("Blocks")
 * )
 */
class OlderNewerBlock extends BlockBase {

  // Current node
  private $node;

  /**
   * {@inheritdoc}
   * Allow All
   */
   public function access(AccountInterface $account, $return_as_object = FALSE) {
     $access = AccessResult::allowedIfHasPermission($account, 'access content');
     return $return_as_object ? $access : $access->isAllowed();
    }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get the created time of the current node
    $this->node = \Drupal::request()->attributes->get('node');

    $newer = $this->generateLink('newer');
    $older = $this->generateLink('older');

    $markup = '';
    if (!empty($older)) {
        $markup .= '<a class="btn btn-tertiary btn-previous" href="'.$older->toString().'">Older</a>';
    }
    if (!empty($newer)) {
        $markup .= '<a class="btn btn-tertiary btn-next pull-right" href="'.$newer->toString().'">Newer</a>';
    }

    return [
        '#cache' => [
            'contexts' => [
                'url'
            ],
            'max-age' => 0
        ],
        '#markup' => $markup
    ];
  }

  /**
   * Lookup the next or previous node
   *
   * @param  string $direction    either 'next' or 'previous'
   * @param  string $created_time a Unix time stamp
   * @return string               an html link to the next or previous node
   */
  private function generateLink($direction = 'newer') {

    if ($direction === 'newer') {
      $comparison_operator = '>';
      $sort = 'ASC';
    }
    elseif ($direction === 'older') {
      $comparison_operator = '<';
      $sort = 'DESC';
    }

    //Lookup 1 node younger (or older) than the current node
    $query = \Drupal::entityQuery('node');
    $next = $query->condition('created', $this->node->getCreatedTime(), $comparison_operator)
      ->condition('type', $this->node->getType())
      ->sort('created', $sort)
      ->range(0, 1)
      ->execute();

    //If this is not the youngest (or oldest) node
    if (!empty($next) && is_array($next)) {
      $next = array_values($next);
      $next = $next[0];

      //Find the alias of the next node
      $next_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$next);

      //Build the URL of the next node
      return Url::fromUri('base:/' . $next_url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // The "Help" block must be cached per URL: help is defined for a
    // given path, and does not come with any access restrictions.
    return Cache::mergeContexts(parent::getCacheContexts(), ['url']);
  }
}
