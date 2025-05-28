<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Database\Post;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

use WP_Post;

class Repository extends BaseRepository implements RepositoryContract
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['post_type'])) {
            throw new \InvalidArgumentException("Post type must be configured");
        }
    }

    public function find($id, array $options = []): ?WP_Post
    {
        $post = get_post($id);
        return $post instanceof WP_Post ? $post : null;
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $defaults = [
            'post_type' => $this->config['post_type'],
            'posts_per_page' => $options['limit'] ?? -1,
            'post_status' => 'publish'
        ];
        
        return get_posts(array_merge($defaults, $criteria));
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): int
    {
        $defaults = [
            'post_type' => $this->config['post_type'],
            'post_status' => 'publish'
        ];
        
        return wp_insert_post(array_merge($defaults, $data));
    }
    
    public function update($id, array $data): int
    {
        $data['ID'] = $id;
        return wp_update_post($data);
    }

    public function delete($id): bool
    {
        return wp_delete_post($id, true) !== false;
    }
}