<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Database\Taxonomy;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

use WP_Term;

class Repository extends BaseRepository implements RepositoryContract
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['taxonomy'])) {
            throw new \InvalidArgumentException("Taxonomy must be configured");
        }
    }

    public function find($termId, array $options = []): ?WP_Term
    {
        $term = get_term($termId, $this->config['taxonomy']);
        return ($term instanceof WP_Term && !is_wp_error($term)) ? $term : null;
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $defaults = [
            'taxonomy' => $this->config['taxonomy'],
            'hide_empty' => $options['hide_empty'] ?? false,
            'number' => $options['limit'] ?? 0
        ];
        
        $args = array_merge($defaults, $criteria);
        $terms = get_terms($args);
        
        return is_wp_error($terms) ? [] : $terms;
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): int
    {
        $defaults = [
            'taxonomy' => $this->config['taxonomy']
        ];
        
        $result = wp_insert_term(
            $data['name'],
            $this->config['taxonomy'],
            array_merge($defaults, $data)
        );
        
        return is_wp_error($result) ? 0 : $result['term_id'];
    }
    
    public function update($termId, array $data): bool
    {
        $result = wp_update_term(
            $termId,
            $this->config['taxonomy'],
            $data
        );
        
        return !is_wp_error($result);
    }

    public function delete($termId): bool
    {
        $result = wp_delete_term(
            $termId,
            $this->config['taxonomy']
        );
        
        return !is_wp_error($result) && $result;
    }

    public function assignToPost(int $termId, int $postId, bool $append = false): bool
    {
        $result = wp_set_post_terms(
            $postId,
            [$termId],
            $this->config['taxonomy'],
            $append
        );
        
        return !is_wp_error($result);
    }

    public function getTermHierarchy(): array
    {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT term_id, parent FROM $wpdb->term_taxonomy WHERE taxonomy = %s",
            $this->config['taxonomy']
        );
        
        $results = $wpdb->get_results($query);
        $hierarchy = [];
        
        foreach ($results as $row) {
            if ($row->parent > 0) {
                $hierarchy[$row->parent][] = $row->term_id;
            }
        }
        
        return $hierarchy;
    }
}