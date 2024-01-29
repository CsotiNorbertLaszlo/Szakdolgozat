<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <title>App</title>
</head>
<body>
    <x-app-layout>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Welcome, {{ Auth::user()->name }}!
        </h2>
    </x-slot>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-semibold">Your Balance:</h2>
                        <div class="flex items-center">
                            <p class="text-xl font-bold">${{ Auth::user()->balance }}</p>
                            <div class="ml-4">
                                <a href="{{ url('/profile#update-balance-section') }}" style="background-color: green" class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                    ADD
                                </a>
                                <a id="openModal" style="background-color: green; cursor: pointer; margin-left: 10px" class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                    BUY STOCK
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div id="myModal" class="modal hidden">
            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="py-4">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <form action="/buystocks" id="buyStocksForm" class="mt-4" method="POST">
                                    <div class="flex items-center mb-4">
                                        <label for="stock">Select a stock:</label>
                                        <select id="stock" name="stock" class="ml-2">
                                            @foreach ($stocks as $stock)
                                                <option value="{{ $stock->company_name }}" data-adjusted-close="{{ $stock->adjusted_close }}">{{ $stock->company_name }}</option>
                                            @endforeach
                                        </select>

                                        <label for="amount" class="ml-4">Amount:</label>
                                        <input type="number" id="amount" name="amount" min="1">
                                        <label for="adjustedClose">Adjusted Close:</label>
                                        <input type="text" id="adjustedClose" name="adjustedClose" readonly><br><br>
                                    </div>

                                    <label for="total">Total:</label>
                                    <input type="text" id="total" name="total" readonly>

                                    <button id="buyButton" style="background-color: green; color: white" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-4">
                                        BUY
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-4">Available Stocks Information</h2>
                    <div class="overflow-x-auto">
                        <table id="stock-table" class="min-w-full border rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th onclick="sortTable(0)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Ticker</th>
                                <th onclick="sortTable(1)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Company Name</th>
                                <th onclick="sortTable(2)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Open</th>
                                <th onclick="sortTable(3)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Dividend Yield</th>
                                <th onclick="sortTable(4)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Dividend Amount</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($stocks as $stock)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">{{ $stock->ticker_symbol }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">{{ $stock->company_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">{{ $stock->open }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">{{ $stock->dividend_yield }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap border-b">{{ $stock->dividend_amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <div class="mt-4">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <script>
            document.getElementById('stock').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var adjustedClose = selectedOption.getAttribute('data-adjusted-close');


                document.getElementById('adjustedClose').value = adjustedClose;


                calculateTotal();
            });


            document.getElementById('amount').addEventListener('change', calculateTotal);

            function calculateTotal() {
                var adjustedClose = parseFloat(document.getElementById('adjustedClose').value);
                var amount = parseInt(document.getElementById('amount').value);
                var total = adjustedClose * amount;


                document.getElementById('total').value = total.toFixed(2);
            }

        </script>
        <script>
            function sortTable(columnIndex) {
                let table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("stock-table");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("TD")[columnIndex];
                        y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
        </script>
        <script>

            var modal = document.getElementById("myModal");

            var btn = document.getElementById("openModal");

            var span = document.querySelector("#myModal .close");


            btn.onclick = function() {
                modal.classList.remove("hidden");
            }


            span.onclick = function() {
                modal.classList.add("hidden");
            }


            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#buyButton').click(function(e){
                    e.preventDefault();

                    // Gather data from the form
                    var stockId = $('#stock').val();
                    var amount = $('#amount').val();

                    console.log("Stock ID: " + stockId);
                    console.log("Amount: " + amount);

                    // Send AJAX request
                    $.ajax({
                        type: "POST",
                        url: "{{ route('buystocks') }}",
                        data: {
                            stock_id: stockId,
                            quantity: amount,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {

                            alert(data.message);
                        },
                        error: function(xhr, status, error) {

                            var errorMessage = xhr.responseJSON.error;
                            alert(errorMessage);
                        }
                    });
                });
            });

        </script>
    </div>
</x-app-layout>

</body>
</html>
