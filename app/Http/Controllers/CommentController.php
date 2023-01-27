<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request) {
        Comment::create($request->validated());

        return redirect()->route('home.tickets.show', $request->ticket_id);
    }
}
