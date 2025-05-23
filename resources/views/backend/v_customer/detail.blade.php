@extends('backend.v_layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-2">{{ $judul }}</h1>
    <p class="text-muted mb-4">{{ $sub }}</p>

    <div class="card shadow-sm">
        <div class="card-body row">
            <div class="col-md-4 text-center">
                <img src="{{ asset('storage/img-customer/' . $customer->user->foto) }}" 
                     alt="Foto Customer" 
                     class="img-fluid rounded shadow-sm mb-3" 
                     style="max-width: 150px;">
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Nama</th>
                        <td>:</td>
                        <td>{{ $customer->user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>:</td>
                        <td>{{ $customer->user->email }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>:</td>
                        <td>{{ $customer->user->hp }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td>{{ $customer->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Kode Pos</th>
                        <td>:</td>
                        <td>{{ $customer->pos }}</td>
                    </tr>
                </table>
                <a href="{{ route('backend.customer.edit', $customer->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Ubah Data
                </a>
                <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
