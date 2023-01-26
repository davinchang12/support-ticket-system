@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a href="{{ route('home.users.index') }}" class="btn btn-primary">Back</a>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Edit User') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('home.users.update', $user) }}" method="POST">
                            @method('put')
                            @csrf

                            <div class="mb-3">
                                <label for="inputName" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="inputName"
                                    value="{{ old('name', $user->name) }}" disabled>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="inputEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="inputEmail"
                                    value="{{ old('email', $user->email) }}" disabled>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="inputRole" class="form-label">Role</label>
                                <select name="role" id="inputRole" class="form-control">
                                    <option value="agent">Agent</option>
                                    <option value="customer">Customer</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
