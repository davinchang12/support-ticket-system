@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card mt-3">
                    <div class="card-header">{{ __('Ticket Logs') }}</div>

                    <div class="card-body">
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
                                                    href="{{ route('home.ticketlogs.show', $ticket->id) }}">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            No ticket log found.
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
