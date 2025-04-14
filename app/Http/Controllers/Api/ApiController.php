<?php
    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Purchase;
    use Illuminate\Support\Facades\DB;
    use App\Models\Book;

    class ApiController extends Controller
    {
        public function index(){

            //Gets current month and year
            $year = date('Y');
            $month = date('n');

            //queries DB, gets books and counts purchases using HasMany relationship
            $books = Book::withCount(['purchases as purchases_count' => function ($query) use ($year, $month) {
                $query->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            }]) 
            ->orderByDesc('purchases_count')
            ->take(10)
            ->get()
            ->makeHidden(['purchases_count', 'created_at', 'updated_at', 'price']); //hides unwanted columns

            return response()->json($books);
        }
    }
?>