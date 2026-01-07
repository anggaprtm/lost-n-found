{{-- resources/views/reports/public_index.blade.php --}}

@extends('layouts.app') {{-- Gunakan ini, bukan <x-app-layout> --}}

@section('content')
    {{-- 
        Kita tambahkan div dengan padding-top (pt-16) 
        agar kontennya tidak tertutup oleh navbar yang posisinya 'fixed'.
    --}}
    <div class="pt-16"> 
        @include('reports._public_reports_list')
    </div>
@endsection