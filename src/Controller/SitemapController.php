<?php

/**
 * @file
 * Contains \Drupal\drupalbase\Controller\SitemapController.
 */

namespace Drupal\drupalbase\Controller;

use Drupal\Core\Controller\ControllerBase;

class SitemapController extends ControllerBase {
    public function content() {
        return [
            '#theme' => 'drupalbase_sitemap',
            '#title' => 'Sitemap',
            '#markup' => 'Sitemap',
            '#articles' => $this->getContent('article'),
            '#pages' => $this->getContent('page')
        ];
    }
    
    protected function getContent($type = 'page') {
        $content = array();
        
        $result = \Drupal::entityQuery('node')
            ->condition('type', $type)
            ->condition('status', 1)
            ->execute();
        
        if (!empty($result)) {
            $nodes = node_load_multiple($result);
            foreach ($nodes as $node) {
                $content[] = [
                    'title' => $node->getTitle(),
                    'url' => $node->url()
                ];
            }
        }
        
        return $content;
    }
}