<?php

namespace VCComponent\Laravel\Post\Test\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\Post\Entities\Post;
use VCComponent\Laravel\Post\Test\TestCase;

class AdminPageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_post_by_admin_router()
    {
        $data = factory(Post::class)->make()->toArray();

        $response = $this->json('POST', 'api/post-management/admin/pages', $data);

        $response->assertStatus(200);
        $response->assertJson(['data' => $data]);

        $this->assertDatabaseHas('posts', $data);
    }

    /**
     * @test
     */
    public function can_update_post_by_admin_router()
    {
        $post = factory(Post::class)->make();
        $post->save();

        $id          = $post->id;
        $post->title = 'update title';
        $data        = $post->toArray();

        $response = $this->json('PUT', 'api/post-management/admin/pages/' . $id, $data);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title' => $data['title'],
            ],
        ]);

        $this->assertDatabaseHas('posts', $data);
    }

    /**
     * @test
     */
    public function can_delete_post_by_admin_router()
    {
        $post = factory(Post::class)->create()->toArray();

        $this->assertDatabaseHas('posts', $post);

        $response = $this->call('DELETE', 'api/post-management/admin/pages/' . $post['id']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('posts', $post);
    }

    /**
     * @test
     */
    public function can_retrieve_post_item_by_admin_router()
    {
        $post = factory(Post::class)->create();

        $response = $this->call('GET', 'api/post-management/admin/pages/' . $post->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'title'       => $post->title,
                'description' => $post->description,
                'content'     => $post->content,
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_retrieve_post_list_by_admin_router()
    {
        $posts = factory(Post::class, 5)->create();

        $response = $this->call('GET', 'api/post-management/admin/pages');

        $response->assertStatus(200);
    }
}
