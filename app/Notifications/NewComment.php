<?php

namespace App\Notifications;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var \App\Comment */
    public $comment;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  \App\User  $user
     * @return array
     */
    public function via($user)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
        return (new MailMessage)
                ->subject('New Comment')
                ->greeting("Hello! {$this->comment->publication->author->name}")
                ->line('A new comment was added to your publication')
                ->line("Publication: {$this->comment->publication->title}")
                ->line("Comment: {$this->comment->content}")
                ->line('Thank you for using our application!');
    }
}
