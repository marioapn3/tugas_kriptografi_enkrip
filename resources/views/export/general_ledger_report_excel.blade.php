{{-- <h1 style="text-align: center; ">General Ledger Report {{ $start_date }} - {{ $end_date }}</h1> --}}
<h1 style="text-align: center; ">General Ledger Report </h1>
<table style="width: 100%;border: 1px solid black;
  border-collapse: collapse;">
    <thead style="border: 1px solid black;
  border-collapse: collapse;">
        {{-- "Code - Account", "Date", "Transaction Number", "Credit", "Debit", "Balance" --}}
        <tr style="background-color: #e6e6e7;">
            <th scope="col">Code - Account</th>
        </tr>
    </thead>
    <tbody style="">
        @foreach ($accounts as $account)
            <tr>
                <td>
                    <table>
                        <tr>
                            <td> {{ $account->code }} - {{ $account->name }}</td>
                        </tr>
                        <tr>
                            <td> Balance : Rp. {{ number_format($account->getTotalAmount(), 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="border: 1px solid black; border-collapse: collapse;">
                <td>
                    <table border="1">
                        <tr>
                            <td>Date</td>
                            <td>Transaction Number</td>
                            <td>Credit</td>
                            <td>Debit</td>
                        </tr>
                        @foreach ($account->journalDetails as $item)
                            <tr>
                                <td>{{ $item->journal->date }}</td>
                                <td>{{ $item->journal->no_transaction }}</td>
                                <td>Rp. {{ $item->credit }}</td>
                                <td>Rp. {{ $item->credit }}</td>
                            </tr>
                        @endforeach
                    </table>

                </td>
                <td>
                    <hr>
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
