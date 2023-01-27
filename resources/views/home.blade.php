@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex gap-1">
                        <div class="card">
                            <div class="card-header text-center">Total tickets</div>
                            <div class="card-body text-center">
                                {{ $totalTickets }}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-center">Total open tickets</div>
                            <div class="card-body text-center">
                                {{ $openTickets }}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-center">Total in progress tickets</div>
                            <div class="card-body text-center">
                                {{ $inProgressTickets }}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-center">Total cancelled tickets</div>
                            <div class="card-body text-center">
                                {{ $cancelledTickets }}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-center">Total completed tickets</div>
                            <div class="card-body text-center">
                                {{ $completedTickets }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
