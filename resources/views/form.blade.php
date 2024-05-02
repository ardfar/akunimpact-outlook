<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Record - Akunimpact Outlook</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Tambahkan link untuk menggunakan Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>

    </style>
</head>
<body>
    @if (session('success'))
      <script>
        alert({{ session('success') }});
      </script>
    @endif

    {{-- Finance Statistic: START  --}}
    <section class="w-full h-1/2 grid grid-cols-12 gap-x-4 px-6 mt-6">
        <form action="{{ route("store-record") }}" method="post" class="col-span-12 h-full w-full" id="form" onsubmit="return confirm('Are you sure you want to submit this form?');">
            <div class="relative w-full h-fit">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold align-middle">Add Record</h2>
    
                    <button id="submit" class="flex items-center justify-evenly gap-x-2 px-4 py-2 text-center bg-green-500 text-white rounded-lg">
                        <i data-feather="plus" class="w-4 h-4 text-white"></i>
                        Submit
                    </button>
                </div>
            </div>


            @csrf
            <div class="bg-gray-200 rounded-lg px-6 py-8 mb-4 grid grid-cols-12 w-full h-fit gap-x-4 gap-y-6">
                <div class="flex items-center justify-center col-span-12 w-full h-fit gap-x-6">
                    <label for="" class="font-semibold">Date of Transaction</label>
                    <input type="date" id="trx_time" name="trx_time" required class="px-2 py-1 rounded-md w-1/3">
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Code</label>
                    <input type="text" id="code" name="code" required class="px-2 py-1 rounded-md w-3/5">
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Category</label>
                    <select name="category" id="category" class="px-2 py-1 rounded-md w-3/5 capitalize">
                        <option value="account">account</option>
                        <option value="topup">topup</option>
                        <option value="showcase">showcase</option>
                        <option value="midman">midman</option>
                        <option value="barter">barter</option>
                    </select>
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Nett Price</label>
                    <input type="number" id="nett_price" name="nett_price" required class="px-2 py-1 rounded-md w-3/5">
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Selling Price</label>
                    <input type="number" id="deal_price" name="deal_price" required class="px-2 py-1 rounded-md w-3/5">
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Buyer Payment</label>
                    <select name="buyer_payments" id="buyer_payments" class="px-2 py-1 rounded-md w-3/5 capitalize">
                        <option value="Other Bank">other bank</option>
                        <option value="DANA">Dana</option>
                        <option value="E-Wallet">Other E-wallet</option>
                        <option value="BCA">BCA</option>
                        <option value="Bank Jago">Jago</option>
                    </select>
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Seller Payment</label>
                    <select name="seller_payments" id="seller_payments" class="px-2 py-1 rounded-md w-3/5 capitalize">
                        <option value="Other Bank">other bank</option>
                        <option value="DANA">Dana</option>
                        <option value="E-Wallet">Other E-wallet</option>
                        <option value="BCA">BCA</option>
                        <option value="Bank Jago">Jago</option>
                    </select>
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Impact Secure</label>
                    <select name="impact_secure" id="impact_secure" class="px-2 py-1 rounded-md w-3/5 capitalize">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="flex items-center justify-between col-span-6 w-full h-fit">
                    <label for="" class="font-semibold">Handler</label>
                    <select name="handler" id="handler" class="px-2 py-1 rounded-md w-3/5 capitalize">
                        <option value="Clay">Clay</option>
                        <option value="Micva">Micva</option>
                        <option value="Ryu">Ryu</option>
                        <option value="Aleph">Aleph</option>
                    </select>
                </div>
            </div>
        </form>
    </section>
    {{-- Finance Statistic: END  --}}

    <script>
        $(document).ready(function(){
            feather.replace();
        })
    </script>
</body>
</html>