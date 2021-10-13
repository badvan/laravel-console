<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddNewArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $title;
    protected $link;
    protected $description;
    protected $date;
    protected $author;
    protected $img;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $link, $description, $date, $author, $img)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->date = $date;
        $this->author = $author;
        $this->img = $img;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $article = Article::create([
            'title' => $this->title,
            'link' => $this->link,
            'description' => $this->description,
            'date' => $this->date,
            'author' => $this->author,
            'img' => $this->img
        ]);
    }
}
