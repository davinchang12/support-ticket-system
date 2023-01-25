@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.labels.index') }}" class="btn btn-primary">Back</a>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Edit Label') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('home.labels.update', $label) }}" method="POST">
                            @method('put')
                            @csrf

                            <div class="mb-3">
                                <label for="inputName" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="inputName"
                                    value="{{ old('name', $label->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
