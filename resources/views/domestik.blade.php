<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Domestic Destination - RajaOngkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #suggestions {
            list-style-type: none;
            padding: 0;
            margin-top: 0.25rem;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.25rem;
            display: none;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
        }

        #suggestions li {
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        #suggestions li:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1>Cari Domestic Destination</h1>
        <div class="mb-3">
            <label for="search" class="form-label">Masukkan kata kunci:</label>
            <input type="text" class="form-control" id="search" placeholder="Contoh: Jakarta">
            <ul id="suggestions" class="list-group"></ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    li.textContent = `${item.id}`;
                    li.style.cursor = 'pointer';
                    li.addEventListener('click', function() {
                        searchInput.value = this.textContent; // Mengisi input dengan ID
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
</body>

</html>
