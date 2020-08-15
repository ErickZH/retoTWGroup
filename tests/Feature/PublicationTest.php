<?php

namespace Tests\Feature;

use App\Comment;
use App\Publication;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_load_the_publications_page_only_authenticated_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->get(route('publications.index'))
             ->assertOk()
             ->assertSee('Publications');
    }

    /** @test */
    public function it_not_allow_to_view_the_page_of_publications_to_guests()
    {
        $this->get(route('publications.index'))
             ->assertRedirect('login');
    }

    /** @test */
    public function it_load_all_the_publications()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
             ->get(route('publications.index'))
             ->assertOk()
             ->assertSee('Publications')
             ->assertSee($publication->title)
             ->assertSee($publication->content)
             ->assertSee($user->name);
    }

    /** @test */
    public function it_displays_a_message_if_there_are_no_publications()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->get(route('publications.index'))
             ->assertOk()
             ->assertSee('Publications')
             ->assertSee('There are no amazing publications');
    }

    /** @test */
    public function it_allows_see_form_to_create_publication()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->get(route('publications.create'))
             ->assertOk()
             ->assertSee('Create Publication');
    }

    /** @test */
    public function it_not_allow_see_form_to_create_publication_guests()
    {
        $this->get(route('publications.create'))
             ->assertRedirect('login');
    }

    /** @test */
    public function it_allows_create_a_new_publication()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->postJson(route('publications.store'), [
            'title' => 'Laravel',
            'content' => 'The Laravel Framework',
        ])->assertRedirect(route('publications.index'));

        $this->assertDatabaseHas('publications', [
           'title' => 'Laravel',
            'content' => 'The Laravel Framework',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_validated_the_title_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('publications.store'), [
            'title' => null,
            'content' => 'The Laravel Framework',
        ])->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function it_validated_the_title_must_be_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('publications.store'), [
            'title' => 123,
            'content' => 'The Laravel Framework',
        ])->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function it_validated_the_title_must_be_max_255_characters()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('publications.store'), [
            'title' => Str::random(256),
            'content' => 'The Laravel Framework',
        ])->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function it_validated_the_content_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('publications.store'), [
            'title' => 'Laravel',
            'content' => null,
        ])->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function it_validated_the_content_must_be_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('publications.store'), [
            'title' => 'Laravel',
            'content' => null,
        ])->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function it_show_the_button_to_add_a_new_publication()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get(route('publications.store'))
             ->assertOk()
             ->assertSee('Add new publication');
    }

    /** @test */
    public function it_allows_logged_users_to_see_the_detail_of_a_publication()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
             ->get(route('publications.show', $publication->id))
             ->assertOk()
             ->assertSee($publication->title)
             ->assertSee($publication->content);
    }

    /** @test */
    public function it_does_not_allow_guests_to_view_the_detail_of_a_publication()
    {
        $publication = factory(Publication::class)->create();

        $this->get(route('publications.show', $publication->id))
             ->assertRedirect('login');
    }

    /** @test */
    public function it_see_all_comments_of_a_publication()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();
        $comment = factory(Comment::class)->create(['publication_id' => $publication->id]);

        $this->actingAs($user)
             ->get(route('publications.show', $publication->id))
             ->assertOk()
             ->assertSee($publication->title)
             ->assertSee($publication->content)
             ->assertSee($comment->content)
             ->assertSee($comment->author->name);
    }

    /** @test */
    public function it_displays_a_message_if_the_publication_has_no_comments()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
             ->get(route('publications.show', $publication->id))
             ->assertOk()
             ->assertSee($publication->title)
             ->assertSee($publication->content)
             ->assertSee('Publication has no comments');
    }

    /** @test */
    public function it_displays_a_textarea_for_add_a_new_comment()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
            ->get(route('publications.show', $publication->id))
            ->assertOk()
            ->assertSee($publication->title)
            ->assertSee($publication->content)
            ->assertSee('Add a comment');
    }

    /** @test */
    public function it_allows_adding_a_comment_to_the_publication()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
             ->from(route('publications.show', $publication->id))
             ->postJson(route('publications.comment', $publication->id), [
                 'content' => 'Amazing publication',
             ])
             ->assertRedirect(route('publications.show', $publication->id));

        $this->assertDatabaseHas('comments', [
            'publication_id' => $publication->id,
            'user_id' => $user->id,
            'content' => 'Amazing publication',
        ]);
    }

    /** @test */
    public function it_validated_the_comment_content_is_required()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
             ->from(route('publications.show', $publication->id))
             ->post(route('publications.comment', $publication->id), [
                 'content' => null,
             ])->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function it_validated_the_comment_content_must_be_string()
    {
        $user = factory(User::class)->create();
        $publication = factory(Publication::class)->create();

        $this->actingAs($user)
            ->from(route('publications.show', $publication->id))
            ->post(route('publications.comment', $publication->id), [
                'content' => 123,
            ])->assertSessionHasErrors(['content']);
    }
}
