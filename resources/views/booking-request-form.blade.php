@extends('layouts.app')

@section('title', 'Demande de projet')

@section('content')
    <div class="container mx-auto max-w-4xl py-8">
        <livewire:booking-request-form :bookableId="$bookableId" :bookableType="$bookableType" />
    </div>
@endsection
