@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full">
        <livewire:auth.register-studio />
    </div>
</div>
@endsection
