@extends('layouts.app')
@section('title', 'Activities')


@section('content')
@include('activities.show')
@include('activities.create')
@include('activities.addparticipant_show')

@vite(['resources/css/activities.css'])

@if(sizeof(auth()->user()->getBoxes()) == 0)
    <p class="mt-3">You are currently not a member of any gym.</p>
    <a href="/gyms" class="btn btn-success">Join Gym</a>
@elseif(sizeof($activities) == 0)
    <p class="mt-3">There are currently no activities available.</p>
@else
    <table id="activities-table" class="mt-3 table table-striped table-hover">
        <thead>
            <tr>
                <th>Time</th>
                <th>Activity</th>
                <th class="hide-on-portrait">Gym</th>
                <th>Booked</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                @php
                if ($activity->users()->where('users.id', auth()->user()->id)->exists())
                    $booked_class = "booked-text-color";
                else
                    $booked_class = "";
                @endphp
                <tr class="{{ $booked_class }}" data-toggle="modal" data-target="#showActivityModal" data-id="{{ $activity->id }}">
                    @php
                        $date = new \DateTime($activity->start);
                    @endphp
                    <td>{{ ((int) $date->format('d')) }}{{ $date->format('/n H:i') }}</td>
                    <td>{{ $activity->subject }}</td>
                    <td class="hide-on-portrait">{{ $activity->box->name }}</td>
                    @php
                        $numberOfMembers = $activity->users->count();
                        $isFull = $numberOfMembers >  $activity->maximum_users;
                    @endphp
                    <td><span class="members-booked">{{ $isFull ? $activity->maximum_users : $numberOfMembers }}</span>/{{ $activity->maximum_users }} <span class="members-queued-table">{{ $isFull ? '(' . ($numberOfMembers-$activity->maximum_users) . ')' : '' }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

    @if ($is_admin)
        <button class="btn btn-primary" id="newTraining" onclick='create_activity()' style="margin-bottom: 10px;">Create new workout</button>
    @endif

    @vite(['resources/js/activities.js'])
@endsection
