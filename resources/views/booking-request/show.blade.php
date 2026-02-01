@extends('layouts.app')

@section('title', 'Demande de projet - Ink&Pik')

@section('content')
    <div class="bg-noir-profond min-h-screen py-10 px-4">
        <div class="container mx-auto max-w-3xl">
            @livewire('booking-request-form', ['bookableId' => $bookableId, 'bookableType' => $bookableType])
        </div>
    </div>
@endsection
