{{-- <h1 style="text-align: center; ">General Ledger Report {{ $start_date }} - {{ $end_date }}</h1> --}}
<h1 style="text-align: center; ">Balance Report </h1>
<p style="text-align: center">{{ $start_date . '  until  ' . $end_date }}</p>

<table style="width: 100%;border: 1px solid black;
  border-collapse: collapse;">
    <thead style="border: 1px solid black;
  border-collapse: collapse;">
        <tr style="background-color: #e6e6e7;">

            <th>Name</th>
            <th>Code</th>

            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr>

                <td>{{ $account['name'] }}</td>
                <td>{{ $account['code'] }}</td>
                <td>Rp. {{ $account['debit'] }}</td>
                <td>Rp. {{ $account['credit'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2">Total Balance Sheet</td>
            <td>Rp. {{ $total_debit }}</td>
            <td>Rp. {{ $total_credit }}</td>
        </tr>
    </tbody>
</table>
