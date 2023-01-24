@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.tickets.index') }}" class="btn btn-primary">Back</a>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Create new Ticket') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (count($labels) > 0 && count($categories) > 0)
                            <form action="{{ route('home.tickets.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="inputTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" id="inputTitle" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="inputMessage" class="form-label">Message</label>
                                    <textarea class="form-control" name="description" id="inputMessage" rows="10" style="width: 100%;">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Labels</label> <br>
                                    @foreach ($labels as $label)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineLabelCheckbox{{ $loop->iteration }}" name="labels[]"
                                                value="{{ $label->id }}">
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
                                            <input class="form-check-input" type="checkbox" id="inlineCategoryCheckbox{{ $loop->iteration }}" name="categories[]"
                                                value="{{ $category->id }}">
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
                                    <select name="priority" id="inputPriority" class="form-control">
                                        @foreach (App\Models\Ticket::PRIORITY as $priority)
                                            <option value="{{ $priority }}">{{ ucwords($priority) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="formFileMultiple" class="form-label">Attachment (Optional)</label>
                                    <input class="form-control" type="file" name="attachment" id="formFileMultiple">
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        @else
                            Sorry, creating tickets is unavailable at the moment. Please contact our admin via
                            <b>admin@admin.com</b>.
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
