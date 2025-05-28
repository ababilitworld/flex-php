<?php

namespace Ababilithub\FlexPhp\Package\Api\v1\Controller;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardPhpMixin,
    FlexPhp\Package\Api\v1\Facade\Api as Facade,
};

class Api
{
    use StandardPhpMixin;
    private Facade $facade;

    public function __construct()
    {
        $this->facade = new Facade();
    }

    public function render(string $containerId): void
    {
        $root = $this->facade->getTree();

        echo '<div id="' . htmlspecialchars($containerId) . '">';

        if (!$root) {
            echo '<p>Error loading tree data.</p>';
        } else {
            echo $this->buildTree($root);
        }

        echo '</div>';
    }

    private function buildTree(array $node): string
    {
        if (!$node) {
            return '<div></div>';
        }

        $html = '<div class="tree-node">';
        $html .= '<button class="accordion-button" onclick="toggleContent(this)">' . htmlspecialchars($node['title']) . '</button>';
        $html .= '<div class="accordion-content" style="display:none;">';

        foreach ($node['children'] ?? [] as $child) {
            $html .= $this->buildTree($child);
        }

        $html .= '</div></div>';

        return $html;
    }

    public function prepareContent(array $item): string
    {
        $html = '<div class="tree-content">';
        $html .= '<h3>' . htmlspecialchars($item['parent'] . ' . ' . $item['id'] . ': ' . $item['title']) . '</h3>';
        $html .= '<div class="content-details"><p>' . htmlspecialchars($item['details'] ?? '') . '</p></div>';

        if (!empty($item['children'])) {
            $html .= '<div class="child-content">';
            foreach ($item['children'] as $child) {
                $html .= $this->prepareContent($child);
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}

