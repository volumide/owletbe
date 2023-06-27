<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .product th {
            background: green;
            color: #fff;
        }

        td,
        th {
            /* border: 1px solid #dddddd;s */
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th> <img src="{{ asset('assets/logo.png') }}" width="100px"></th>
            <th>
                <p>Status: {{ $content['transactions']['status'] }}</p>
            </th>
        </tr>

        {{-- <tr>  </tr>
		<tr> Amount </tr>
		<tr> Token </tr> --}}
    </table>
    <table class="product">
        <tr>
            <th>Product</th>
            <th>Amount</th>
            <th>Token</th>
            <th>Purchase Code</th>
            <th>Unit</th>
        </tr>
        <tr>
            <td>
                <p>{{ $content['transactions']['product_name'] }} </p>
            </td>
            <td>
                <p>{{ $content['transactions']['amount'] }}</p>
            </td>
            <td>
                @if (isset($mainToken))
                    <p>{{ $mainToken }}</p>
                @else
                    <p></p>
                @endif

            </td>
            <td>
                @if (isset($purchased_code))
                    <p>{{ $purchased_code }}</p>
                @else
                    <p></p>
                @endif
            </td>
            <td>
                @if (isset($mainTokenUnits))
                    <p>{{ $mainTokenUnits }}</p>
                @else
                    <p></p>
                @endif
            </td>
        </tr>
    </table>

    <footer>
        <a href="/">Twitter</a>
        <a href="/">Facebook</a>
        <a href="/">Instagram</a>
    </footer>






    {{-- @if (@isset($purchased_code))
        <p>{{ $purchased_code }}</p>
    @endif --}}


</body>

</html>
