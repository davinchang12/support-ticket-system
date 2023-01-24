@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @can('create tickets')
                    <a href="{{ route('home.tickets.create') }}" class="btn btn-primary">Create ticket</a>
                @endcan

                <div class="card mt-3">
                    <div class="card-header">{{ __('Tickets') }}</div>

                    <div class="card-body">

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (count($tickets) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="align-middle">{{ $ticket->title }}</td>
                                            <td class="align-middle">{{ ucwords($ticket->priority) }}</td>
                                            <td class="align-middle">{{ ucwords($ticket->status) }}</td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="{{ route('home.tickets.show', $ticket->id) }}">Show</a>
                                                @can('edit tickets')
                                                    <a class="btn btn-secondary"
                                                        href="{{ route('home.tickets.edit', $ticket->id) }}">Edit</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No ticket found.
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
