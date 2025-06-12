@extends('v_layouts.app')
@section('content')
    <div class="col-md-12">
        <h2>Cek Ongkir</h2>
        <form action="{{ route('cek-ongkir.hitung') }}" method="POST">
            @csrf
            {{-- <div class="mb-3">
        <label for="origin" class="form-label">Kota Asal (ID)</label>
        <input type="text" name="origin" id="origin" class="form-control" value="6519" readonly>
    </div> --}}

            <div class="mb-3">
                <label for="destination" class="form-label">Kota Tujuan (ID)</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Contoh: Bekasi" required>
                <input type="hidden" id="destination" name="destination" />
                <ul id="suggestions" class="list-group"></ul>
            </div>

            <div class="mb-3">
                <label for="weight" class="form-label">Berat (gram)</label>
                <input type="number" name="weight" id="weight" class="form-control" value="1000" required>
            </div>

            <div class="mb-3">
                <label for="courier" class="form-label">Kurir</label>
                <select name="courier" id="courier" class="form-select">
                    <option value="">Pilih Kurir</option>
                    <option value="jne">JNE</option>
                    <option value="jnt">JNT</option>
                    <option value="sicepat">SI Cepat</option>
                    <option value="tiki">TIKI</option>
                    <option value="pos">POS Indonesia</option>
                </select>
                <small class="form-text text-muted">Pilih satu kurir.</small>
            </div>
            <button type="submit" class="btn btn-primary">Hitung Ongkir</button>
        </form>

        @if (session('result') && isset(session('result')['data']) && is_array(session('result')['data']))
            <hr>
            <h4>Hasil Ongkir</h4>
            @if (count(session('result')['data']) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Layanan</th>
                                <th>Layanan</th>
                                <th>Deskripsi</th>
                                <th>Biaya</th>
                                <th>Estimasi Tiba</th>
                                <th>Bayar</th>
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
                                            <input type="hidden" name="code" value="{{ $item['code'] }}">
                                            <input type="hidden" name="service" value="{{ $item['service'] }}">
                                            <input type="hidden" name="description" value="{{ $item['description'] }}">
                                            <input type="hidden" name="cost" value="{{ $item['cost'] }}">
                                            <input type="hidden" name="etd" value="{{ $item['etd'] }}">
                                            <button type="submit" class="btn btn-sm btn-primary">Pilih Pengiriman</button>
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
        @elseif (session('result') && isset(session('result')['meta']['message']))
            <hr>
            <h4>Hasil Ongkir</h4>
            <p>{{ session('result')['meta']['message'] }}</p>
        @endif
    </div>


    <script>
        const searchInput = document.getElementById('search');
        const suggestionsList = document.getElementById('suggestions');
        const resultDiv = document.getElementById('result');
        let suggestionTimeout;

        searchInput.addEventListener('input', function() {
            const keyword = this.value.trim();
            clearTimeout(suggestionTimeout);

            if (keyword.length >= 3) {
                suggestionTimeout = setTimeout(getDomesticSuggestions(keyword), 300);
            } else {
                suggestionsList.style.display = 'none';
                suggestionsList.innerHTML = '';
                resultDiv.innerHTML = '';
            }
        });

        function getDomesticSuggestions(keyword) {
            return () => {
                fetch(`/domestic-destination?search=${encodeURIComponent(keyword)}`)
                    .then(response => response.json())
                    .then(data => {
                        const results = data.data || [];
                        displaySuggestions(results);
                    })
                    .catch(error => {
                        console.error("Error fetching suggestions:", error);
                        suggestionsList.style.display = 'none';
                        suggestionsList.innerHTML =
                            '<li class="list-group-item list-group-item-danger">Gagal mengambil saran.</li>';
                        setTimeout(() => suggestionsList.style.display = 'none', 2000);
                    });
            };
        }


        function displaySuggestions(results) {
            suggestionsList.innerHTML = '';
            if (results.length > 0) {
                results.slice(0, 10).forEach(item => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item', 'list-group-item-action');
                    li.textContent =
                        `${item.id}, ${item.subdistrict_name}, ${item.city_name}, ${item.province_name}`;
                    li.style.cursor = 'pointer';
                    li.addEventListener('click', function() {
                        searchInput.value =
                            `${item.subdistrict_name}, ${item.city_name}, ${item.province_name}`;
                        document.getElementById('destination').value = item
                            .id; // hanya ID // Mengisi input dengan ID
                        suggestionsList.style.display = 'none';
                        // Anda bisa menambahkan logika lain di sini jika perlu,
                        // misalnya menyimpan ID yang dipilih ke input hidden.
                    });
                    suggestionsList.appendChild(li);
                });
                suggestionsList.style.display = 'block';
            } else {
                suggestionsList.style.display = 'none';
            }
        }

        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !suggestionsList.contains(event.target)) {
                suggestionsList.style.display = 'none';
            }
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
