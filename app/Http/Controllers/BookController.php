<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\BasicHelper;

class BookController extends Controller
{   
    //Returns all books and provides search by aythor/title/month
    public function index(Request $request) {

        $request->validate([
            'titlePhrase' => 'nullable|string',
            'authorPhrase' => 'nullable|string',
        ]);
        $titlePhrase = $request->query('titlePhrase', '');
        $authorPhrase = $request->query('authorPhrase', '');
        $year = $request->query('year', '');
        $month = $request->query('month', '');
        
        $books = Book::with('authors')

        //checks if author search phrase exists, then queries table using "like"  authorPhrase
        ->when($authorPhrase !== '', function ($query) use ($authorPhrase) {
            $query->whereHas('authors', function ($authorQuery) use ($authorPhrase) {
                $authorQuery->where('name', 'like', '%' . $authorPhrase . '%');
            });
        })

        //checks if title search phrase exists, then queries table using "like" = titlePhrase
        ->when($titlePhrase !== '', function ($query) use ($titlePhrase){
            $query->where('title', 'like', '%' . $titlePhrase . '%');
        })

        //if no year value, then counts purchases
        ->when($year === '', function ($query){
            $query->withCount('purchases');
        })

        //if year is set, then counts purchases in exact year/month
        ->when($year !== '', function ($query) use ($year, $month){
            $query->withCount(['purchases as purchases_count' => function ($purchaseQuery) use ($year, $month) {
                $purchaseQuery->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            }])->orderByDesc('purchases_count');
        })
        ->paginate(15)->withQueryString();
        
        //Calls helper class to provide basic options for a frontend select tag
        $optionsFilterByMonth = BasicHelper::getFilterYearMonth(2);

        return Inertia::render('Book', [
            'books' => $books,
            'optionsFilterByMonth' => $optionsFilterByMonth,
        ]);
    }
}
