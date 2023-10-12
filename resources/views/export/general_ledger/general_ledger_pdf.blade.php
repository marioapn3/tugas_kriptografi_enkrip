{{-- <h1 style="text-align: center; ">General Ledger Report {{ $start_date }} - {{ $end_date }}</h1> --}}
<h1 style="text-align: center; ">General Ledger Report </h1>
<p style="text-align: center">{{ $start_date . '  until  ' . $end_date }}</p>

<table style="width: 100%;border: 1px solid black;
  border-collapse: collapse;">
    <thead style="border: 1px solid black;
  border-collapse: collapse;">
        {{-- "Code - Account", "Date", "Transaction Number", "Credit", "Debit", "Balance" --}}
        <tr style="background-color: #e6e6e7;">
            <th scope="col">Code - Account</th>
            <th scope="col">Date</th>
            <th scope="col">Transaction Number</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Balance</th>
        </tr>
    </thead>
    <tbody style="">
        @foreach ($accounts as $account)
            <tr style="border: 1px solid black; border-collapse: collapse;">
                <td>{{ $account->code }} - {{ $account->name }}</td>
                <td>
                    <table>
                        @foreach ($account->journalDetails as $item)
                            <tr>
                                <td>{{ $item->journal->date }}</td>
                            </tr>
                        @endforeach
                    </table>

                </td>
                <td>
                    <table>
                        @foreach ($account->journalDetails as $item)
                            <tr>
                                <td>{{ $item->journal->no_transaction }}</td>
                            </tr>
                        @endforeach
                    </table>

                </td>
                <td>
                    <table>
                        @foreach ($account->journalDetails as $item)
                            <tr>
                                <td>Rp. {{ $item->credit }}</td>
                            </tr>
                        @endforeach
                    </table>

                </td>
                <td>
                    <table>
                        @foreach ($account->journalDetails as $item)
                            <tr>
                                <td>Rp. {{ $item->debit }}</td>
                            </tr>
                        @endforeach
                    </table>

                </td>
                <td class="text-end">Rp. {{ number_format($account->getTotalAmount(), 2, ',', '.') }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
