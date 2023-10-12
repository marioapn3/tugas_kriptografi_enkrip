{{-- <h1 style="text-align: center; ">General Ledger Report {{ $start_date }} - {{ $end_date }}</h1> --}}
<h1 style="text-align: center; ">Income Statement Report </h1>
<p style="text-align: center">{{ $start_date . '  until  ' . $end_date }}</p>

<table style="width: 100%;border: 1px solid black;
  border-collapse: collapse;">
    <thead style="border: 1px solid black;
  border-collapse: collapse;">
        {{-- "Code - Account", "Date", "Transaction Number", "Credit", "Debit", "Balance" --}}
        <tr style="background-color: #e6e6e7;">
            <th scope="col">Code - Account</th>
            <th scope="col">Income</th>
            <th scope="col">Expense</th>
        </tr>
    </thead>
    <tbody style="">
        @foreach ($data as $account)
            <tr style="border: 1px solid black; border-collapse: collapse;">
                <td>{{ $account['code'] }} - {{ $account['name'] }}</td>
                <td>
                    Rp. {{ $account['income'] }}
                </td>
                <td>
                    Rp. {{ $account['expense'] }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td>Total Income Statement</td>
            <td>Rp. {{ $totalIncome }}</td>
            <td>Rp. {{ $totalExpense }}</td>

        </tr>
        @php
            $income_string = str_replace(',', '', $totalIncome);
            $totalIntIncome = intval($income_string);
            $totalIntIncome = (int) $income_string;

            $expense_string = str_replace(',', '', $totalExpense);
            $totalIntExpense = intval($expense_string);
            $totalIntExpense = (int) $expense_string;

        @endphp


        @if ($totalIntIncome > $totalIntExpense)
            <tr style="border: 1px solid black;
  border-collapse: collapse;">
                <td>Total Profit </td>
                <td></td>
                <td> Rp. {{ number_format($totalIntIncome - $totalIntExpense) }}</td>
            </tr>
        @elseif ($totalIntIncome < $totalIntExpense)
            <tr style="border: 1px solid black;
  border-collapse: collapse;">
                <td>Total Loss </td>
                <td></td>
                <td>Rp. {{ number_format($totalIntExpense - $totalIntIncome) }}</td>
            </tr>
        @else
            <tr>
                <td>No Data Found</td>
            </tr>
        @endif

    </tbody>
</table>
