<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

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
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in progress')->count();
        $cancelledTickets = Ticket::where('status', 'cancelled')->count();
        $completedTickets = Ticket::where('status', 'completed')->count();

        return view('home', compact('totalTickets', 'openTickets', 'inProgressTickets', 'cancelledTickets', 'completedTickets'));
    }
}
