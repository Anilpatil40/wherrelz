<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get dashboard data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashBoardData()
    {
        return response()->json(Entry::select('currency', DB::raw('count(*) as count'))
            ->groupBy('currency')
            ->get());
    }
}
