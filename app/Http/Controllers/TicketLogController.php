<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\TicketLog;

class TicketLogController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('ticketlogs.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['labels', 'categories'])->findOrFail($id);
        $labels = Label::all();
        $categories = Category::all();
        $agents = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->get();

        $ticketlogs = TicketLog::with(['user', 'ticket'])
            ->whereHas('ticket', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->get();

        return view('ticketlogs.show', compact('ticket', 'ticketlogs', 'labels', 'categories', 'agents'));
    }
}
