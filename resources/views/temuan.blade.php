@php
    $hideNavigation = true;
@endphp

@extends('layouts.app')

@section('title', 'Selamat Datang di FTMM Lost & Found')

@section('content')
<div>
    @include('reports._public_reports_list')
</div>

<style>
/* Menghilangkan panah default dari tag <details> */
details > summary {
  list-style: none;
}
details > summary::-webkit-details-marker {
  display: none;
}
</style>
@endsection