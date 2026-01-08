@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">

    <div class="row">
        <div class="col-md-8 mx-auto">
            
            <a href="{{ route('petugas.reports.show', $report) }}" class="btn btn-outline-secondary mb-4">
                &larr; Kembali ke Detail Laporan
            </a>

            <h1 class="h3 mb-4">Assign Claim untuk Barang Temuan</h1>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($report->photo)
                                <img src="{{ asset('storage/' . $report->photo) }}" alt="Foto Barang" class="img-fluid rounded">
                            @else
                                <img src="{{ asset('placeholder.jpg') }}" alt="Tidak ada foto" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $report->item_name }}</h4>
                            <p class="text-muted">{{ $report->description }}</p>
                            <ul class="list-unstyled">
                                <li><strong>Kategori:</strong> {{ $report->category->name }}</li>
                                <li><strong>Lokasi:</strong> {{ $report->room->building->name }} - {{ $report->room->name }}</li>
                                <li><strong>Tanggal Ditemukan:</strong> {{ \Carbon\Carbon::parse($report->event_date)->format('d F Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pilih Pengguna untuk Di-assign</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.reports.assign.store', $report) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Nama Pengguna (Mahasiswa)</label>
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Pilih Pengguna --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <p class="text-muted mt-3">
                            Dengan menekan tombol "Assign Claim", sebuah klaim akan dibuat untuk pengguna yang dipilih dan akan langsung ditandai sebagai <strong>"Approved"</strong>. Status barang ini juga akan otomatis berubah menjadi <strong>"Returned"</strong>.
                        </p>

                        <button type="submit" class="btn btn-primary mt-3">Assign Claim</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
