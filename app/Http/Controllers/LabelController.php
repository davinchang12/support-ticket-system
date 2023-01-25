<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::all();
        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        return view('labels.create');
    }

    public function store(StoreLabelRequest $request)
    {
        Label::create($request->validated());

        return redirect()->route('home.labels.index')->with('success', 'Successfully create new label.');
    }

    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    public function update(UpdateLabelRequest $request, Label $label)
    {
        $label->update($request->validated());

        return redirect()->route('home.labels.index')->with('success', 'Successfully edited label.');
    }

    public function destroy(Label $label)
    {
        $label->delete();

        return redirect()->route('home.labels.index')->with('success', 'Successfully deleted label.');
    }
}
