@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @can('create tickets')
                    <a href="{{ route('home.labels.create') }}" class="btn btn-primary">Create label</a>
                @endcan

                <div class="card mt-3">
                    <div class="card-header">{{ __('Label') }}</div>

                    <div class="card-body">

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (count($labels) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($labels as $label)
                                        <tr>
                                            <td class="align-middle">{{ $label->name }}</td>
                                            <td>
                                                <a class="btn btn-secondary"
                                                    href="{{ route('home.labels.edit', $label->id) }}">Edit</a>
                                                <form action="{{ route('home.labels.destroy', $label) }}" method="POST" style="display: inline-block;">
                                                    @method('delete')
                                                    @csrf

                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No label found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
