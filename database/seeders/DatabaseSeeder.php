<?php

namespace Database\Seeders;

use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Author;
use App\Models\Purchase;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $authors = Author::factory(10)->create();
        Book::factory(5)->create(); //Some books without an author and purchases
        $books = Book::factory(20)
        ->create()
        ->each(function ($book) use ($authors) {

            // Gets 1 to 3 authors, attaches books to authors(populates author_book table)
            $authorIds = $authors->random(rand(1, 3))->pluck('id')->toArray();
            $book->authors()->attach($authorIds);

            // Create 1-5 purchases for each book
            Purchase::factory(rand(1, 5))->create([
                'book_id' => $book->id,
            ]);
        });
    }
}
