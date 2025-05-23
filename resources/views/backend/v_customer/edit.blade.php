@extends('backend.v_layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-2">{{ $judul }}</h1>
        <p class="text-muted mb-4">{{ $sub }}</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('backend.customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card shadow-sm p-4">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $customer->user->nama) }}" readonly>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $customer->user->email) }}" readonly>

                </div>

                <div class="form-group">
                    <label for="hp">No HP</label>
                    <input type="text" name="hp" class="form-control" value="{{ old('hp', $customer->user->hp) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $customer->alamat) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="pos">Kode Pos</label>
                    <input type="text" name="pos" class="form-control" value="{{ old('pos', $customer->pos) }}">
                </div>

                <div class="form-group">
                    <label for="foto">Foto</label><br>
                    @if ($customer->user->foto)
                        <img src="{{ asset('storage/img-customer/' . $customer->user->foto) }}" alt="Foto Customer"
                            width="100" class="img-thumbnail mb-2">
                    @endif
                    <input type="file" name="foto" class="form-control-file">
                    <small class="form-text text-muted">Ukuran maksimal 1MB. Format: jpeg, jpg, png, gif.</small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('backend.customer.index', $customer->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
