<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Comment;
use App\Models\TicketLog;

class TicketController extends Controller
{
    public function index()
    {
        $loggedInUserRole = auth()->user()->getRoleNames()->toArray()[0];
        if (in_array($loggedInUserRole, ['admin', 'superadmin'])) {
            $tickets = Ticket::all();
        } else {
            $tickets = Ticket::where('customer_id', auth()->id())
                ->orWhere('agent_id', auth()->id())
                ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $labels = Label::all();
        $categories = Category::all();

        return view('tickets.create', compact('labels', 'categories'));
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'customer_id' => auth()->id(),
        ]);

        if ($request->hasFile('attachment')) {
            $ticket->addMediaFromRequest('attachment')->toMediaCollection();
        }

        foreach ($request->labels as $label) {
            $ticket->labels()->attach($label);
        }

        foreach ($request->categories as $category) {
            $ticket->categories()->attach($category);
        }

        TicketLog::create([
            'user_id' => auth()->id(),
            'ticket_id' => $ticket->id,
            'log' => 'created new ticket',
        ]);

        return redirect()->route('home.tickets.index')->with('success', 'Successfully create new ticket.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['labels', 'categories'])->findOrFail($id);
        $labels = Label::all();
        $categories = Category::all();
        $agents = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->get();
        $comments = Comment::with(['user'])
            ->where('ticket_id', $id)
            ->get();

        return view('tickets.show', compact('ticket', 'labels', 'categories', 'agents', 'comments'));
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['labels', 'categories'])->findOrFail($id);
        $labels = Label::all();
        $categories = Category::all();
        $agents = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->get();

        return view('tickets.edit', compact('ticket', 'labels', 'categories', 'agents'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->only([
            'title',
            'description',
            'priority',
            'status',
            'agent_id'
        ]));

        if ($request->hasFile('attachment')) {
            $mediaItems = $ticket->getMedia();
            $mediaItems[0]->delete();

            $ticket->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        $ticket->labels()->detach();
        foreach ($request->labels as $label) {
            $ticket->labels()->attach($label);
        }

        $ticket->categories()->detach();
        foreach ($request->categories as $category) {
            $ticket->categories()->attach($category);
        }

        TicketLog::create([
            'user_id' => auth()->id(),
            'ticket_id' => $ticket->id,
            'log' => 'updated ticket',
        ]);

        return redirect()->route('home.tickets.index')->with('success', 'Ticket has been updated.');
    }
}
