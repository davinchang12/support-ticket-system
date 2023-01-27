@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.tickets.index') }}" class="btn btn-primary">Back</a>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Edit Ticket') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (count($labels) > 0 && count($categories) > 0)
                            <form action="{{ route('home.tickets.update', $ticket) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('put')
                                @csrf

                                <div class="mb-3">
                                    <label for="inputTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" id="inputTitle"
                                        value="{{ old('title', $ticket->title) }}"
                                        @unlessrole('superadmin|admin') readonly @endunlessrole>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="inputMessage" class="form-label">Message</label>
                                    <textarea class="form-control" name="description" id="inputMessage" rows="10" style="width: 100%;"
                                        @unlessrole('superadmin|admin') readonly @endunlessrole>{{ old('description', $ticket->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Labels</label> <br>
                                    @foreach ($labels as $label)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                id="inlineLabelCheckbox{{ $loop->iteration }}" name="labels[]"
                                                value="{{ $label->id }}"
                                                {{ $ticket->labels->pluck('id')->contains($label->id) ? 'checked' : '' }}
                                                @unlessrole('superadmin|admin') readonly @endunlessrole>
                                            <label class="form-check-label"
                                                for="inlineLabelCheckbox{{ $loop->iteration }}">{{ $label->name }}</label>
                                        </div>
                                    @endforeach
                                    @error('labels')
                                        <br><span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Categories</label> <br>
                                    @foreach ($categories as $category)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                id="inlineCategoryCheckbox{{ $loop->iteration }}" name="categories[]"
                                                value="{{ $category->id }}"
                                                {{ $ticket->categories->pluck('id')->contains($category->id) ? 'checked' : '' }}
                                                @unlessrole('superadmin|admin') readonly @endunlessrole>
                                            <label class="form-check-label"
                                                for="inlineCategoryCheckbox{{ $loop->iteration }}">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                    @error('categories')
                                        <br><span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="inputPriority">Priority</label>
                                    <select name="priority" id="inputPriority" class="form-control"
                                        @unlessrole('superadmin|admin') readonly @endunlessrole>
                                        @foreach (App\Models\Ticket::PRIORITY as $priority)
                                            <option value="{{ $priority }}"
                                                {{ $ticket->priority == $priority ? 'selected' : '' }}>
                                                {{ ucwords($priority) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="inputStatus">Status</label>
                                    <select name="status" id="inputStatus" class="form-control"
                                        @unlessrole('superadmin|admin|agent') readonly @endunlessrole>
                                        @foreach (App\Models\Ticket::STATUS as $status)
                                            <option value="{{ $status }}"
                                                {{ $ticket->status == $status ? 'selected' : '' }}>
                                                {{ ucwords($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    @if ($ticket->getMedia('attachment')->first())
                                        <label for="formFileMultiple" class="form-label">Attachment (Optional)</label>
                                        @role('superadmin|admin')
                                            <input class="form-control" type="file" name="attachment" id="formFileMultiple">
                                        @endrole

                                        <img src="{{ $ticket->getMedia('attachment')->first()->getUrl() }}" alt="">
                                    @endif
                                </div>

                                @role('superadmin|admin')
                                    <div class="mb-3">
                                        <label class="form-label" for="inputUserId">Assign Agent</label>
                                        <select name="agent_id" id="inputUserId" class="form-control">
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}"
                                                    {{ $ticket->agent_id == $agent->id ? 'selected' : '' }}>
                                                    {{ ucwords($agent->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        @else
                            Sorry, editting tickets is unavailable at the moment. Please contact our admin via
                            <b>admin@admin.com</b>.
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
