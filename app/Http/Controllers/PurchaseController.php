<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    //Stores a book in "purchases" table, simulates a purchase 
    public function store(Book $book) {
        Purchase::create([
            'book_id' => $book->id,
        ]);

        return back()->with('success', 'Pirkums veiksmīgs!');;
    }
}
