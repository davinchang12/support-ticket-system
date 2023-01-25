@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @can('create tickets')
                    <a href="{{ route('home.categories.create') }}" class="btn btn-primary">Create category</a>
                @endcan

                <div class="card mt-3">
                    <div class="card-header">{{ __('Category') }}</div>

                    <div class="card-body">

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (count($categories) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="align-middle">{{ $category->name }}</td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="{{ route('home.categories.show', $category->id) }}">Show</a>
                                                <a class="btn btn-secondary"
                                                    href="{{ route('home.categories.edit', $category->id) }}">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No category found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
