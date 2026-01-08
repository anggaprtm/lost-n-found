{{-- resources/views/reports/public_index.blade.php --}}

@extends('layouts.app') {{-- Gunakan ini, bukan <x-app-layout> --}}

@section('content')
    <div class="pt-16"> 
        @include('reports._public_reports_list')
    </div>
@endsection