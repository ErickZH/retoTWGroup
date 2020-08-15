<?php

namespace Tests\Feature;

use App\Comment;
use App\Notifications\NewComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class NewCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function to_mail_returns_a_mail_message_with_the_correct_data()
    {
        $comment = factory(Comment::class)->create();

        $notification = (new NewComment($comment))->toMail($comment->publication->author);

        $this->assertInstanceOf(MailMessage::class, $notification);
        $this->assertEquals($notification->subject, 'New Comment');
        $this->assertEquals($notification->greeting, "Hello! {$comment->publication->author->name}");
    }
}
