@extends('v_layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Cek Ongkir</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cek-ongkir.hitung') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="destination" class="form-label">Kota Tujuan</label>
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Contoh: Bekasi" required>
                                <input type="hidden" id="destination" name="destination" />
                                <ul id="suggestions" class="list-group mt-1"></ul>
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (gram)</label>
                                <input type="number" name="weight" id="weight" class="form-control" value="1000"
                                    required>
                            </div>
                            <br>
                            <div class="mb-3">
                                <select name="courier" id="courier" class="form-select">
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="jnt">JNT</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS Indonesia</option>
                                </select>
                                <small class="text-muted">Pilih salah satu kurir.</small>
                            </div>
                            <br>
                            <button type="submit" class="primary-btn add-to-cart" style="background-color: #F26645; color: white;">Hitung Ongkir</button>
                        </form>
                    </div>
                </div>

                {{-- Hasil Ongkir --}}
                @if (session('result') && isset(session('result')['data']) && is_array(session('result')['data']))
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Hasil Ongkir</h5>
                        </div>
                        <div class="card-body">
                            @if (count(session('result')['data']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kode Layanan</th>
                                                <th>Layanan</th>
                                                <th>Deskripsi</th>
                                                <th>Biaya</th>
                                                <th>Estimasi Tiba</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('result')['data'] as $item)
                                                <tr>
                                                    <td>{{ $item['code'] }}</td>
                                                    <td>{{ $item['service'] }}</td>
                                                    <td>{{ $item['description'] }}</td>
                                                    <td>Rp {{ number_format($item['cost'], 0, ',', '.') }}</td>
                                                    <td>{{ $item['etd'] }}</td>
                                                    <td>
                                                        <form action="{{ route('order.update-ongkir') }}" method="POST">
                                                            @csrf
                                                            @foreach (['code', 'service', 'description', 'cost', 'etd'] as $key)
                                                                <input type="hidden" name="{{ $key }}"
                                                                    value="{{ $item[$key] }}">
                                                            @endforeach
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success">Pilih</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>Tidak ada hasil ongkir untuk kurir yang dipilih.</p>
                            @endif
                        </div>
                    </div>
                @elseif (session('result') && isset(session('result')['meta']['message']))
                    <div class="alert alert-warning mt-4">
                        <strong>Info:</strong> {{ session('result')['meta']['message'] }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Script Auto Suggest --}}
    <script>
        const searchInput = document.getElementById('search');
        const suggestionsList = document.getElementById('suggestions');

        let suggestionTimeout;
        searchInput.addEventListener('input', function() {
            const keyword = this.value.trim();
            clearTimeout(suggestionTimeout);

            if (keyword.length >= 3) {
                suggestionTimeout = setTimeout(fetchSuggestions(keyword), 300);
            } else {
                suggestionsList.style.display = 'none';
                suggestionsList.innerHTML = '';
            }
        });

        function fetchSuggestions(keyword) {
            return () => {
                fetch(`/domestic-destination?search=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        const results = data.data || [];
                        showSuggestions(results);
                    })
                    .catch(() => {
                        suggestionsList.innerHTML =
                            '<li class="list-group-item text-danger">Gagal mengambil saran.</li>';
                        suggestionsList.style.display = 'block';
                    });
            };
        }

        function showSuggestions(results) {
            suggestionsList.innerHTML = '';
            if (results.length > 0) {
                results.slice(0, 10).forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent =
                        `${item.id}, ${item.subdistrict_name}, ${item.city_name}, ${item.province_name}`;
                    li.style.cursor = 'pointer';
                    li.onclick = function() {
                        searchInput.value =
                        `${item.subdistrict_name}, ${item.city_name}, ${item.province_name}`;
                        document.getElementById('destination').value = item.id;
                        suggestionsList.style.display = 'none';
                    };
                    suggestionsList.appendChild(li);
                });
                suggestionsList.style.display = 'block';
            } else {
                suggestionsList.style.display = 'none';
            }
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                suggestionsList.style.display = 'none';
            }
        });
    </script>
@endsection
