{{-- <h1 style="text-align: center; ">General Ledger Report {{ $start_date }} - {{ $end_date }}</h1> --}}
<h1 style="text-align: center; ">Balance Report </h1>
<p style="text-align: center">{{ $start_date . '  until  ' . $end_date }}</p>

<table style="width: 100%;border: 1px solid black;
  border-collapse: collapse;">
    <thead style="border: 1px solid black;
  border-collapse: collapse;">
        {{-- "Code - Account", "Date", "Transaction Number", "Credit", "Debit", "Balance" --}}
        <tr style="background-color: #e6e6e7;">
            <th scope="col">Code - Account</th>

            <th scope="col">Balance</th>
        </tr>
    </thead>
    <tbody style="">
        @foreach ($accounts as $account)
            <tr style="border: 1px solid black; border-collapse: collapse;">
                <td>{{ $account->code }} - {{ $account->name }}</td>
                <td class="text-end">Rp. {{ number_format($account->getTotalAmount(), 2, ',', '.') }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
