@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.ticketlogs.index') }}" class="btn btn-primary">Back</a>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Ticket detail') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (count($labels) > 0 && count($categories) > 0)
                            <div class="mb-3">
                                <label for="inputTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="inputTitle"
                                    value="{{ $ticket->title }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="inputMessage" class="form-label">Message</label>
                                <textarea class="form-control" name="description" id="inputMessage" rows="10" style="width: 100%;" disabled>{{ $ticket->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Labels</label> <br>
                                @foreach ($labels as $label)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                            id="inlineLabelCheckbox{{ $loop->iteration }}" name="labels[]"
                                            value="{{ $label->id }}"
                                            {{ $ticket->labels->pluck('id')->contains($label->id) ? 'checked' : '' }}
                                            disabled>
                                        <label class="form-check-label"
                                            for="inlineLabelCheckbox{{ $loop->iteration }}">{{ $label->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categories</label> <br>
                                @foreach ($categories as $category)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox"
                                            id="inlineCategoryCheckbox{{ $loop->iteration }}" name="categories[]"
                                            value="{{ $category->id }}"
                                            {{ $ticket->categories->pluck('id')->contains($category->id) ? 'checked' : '' }}
                                            disabled>
                                        <label class="form-check-label"
                                            for="inlineCategoryCheckbox{{ $loop->iteration }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="inputPriority">Priority</label>
                                <select name="priority" id="inputPriority" class="form-control" disabled>
                                    @foreach (App\Models\Ticket::PRIORITY as $priority)
                                        <option value="{{ $priority }}"
                                            {{ $ticket->priority == $priority ? 'selected' : '' }}>
                                            {{ ucwords($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                @if ($ticket->getMedia('attachment')->first())
                                    <label for="formFileMultiple" class="form-label">Attachment (Optional)</label>
                                    <img src="{{ $ticket->getMedia('attachment')->first()->getUrl() }}" alt="">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="inputTitle" class="form-label">Log</label>
                                @foreach ($ticketlogs as $log)
                                    <p>{{ $log->created_at }} : {{ $log->log }} (by : {{ $log->user->name }})</p>
                                @endforeach
                            </div>
                        @else
                            Sorry, tickets detail is unavailable at the moment. Please contact our admin via
                            <b>admin@admin.com</b>.
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
