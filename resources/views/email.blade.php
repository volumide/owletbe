<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owletpay Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #8bc34a;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tfoot td {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #888;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Forgot Password</h2>
        </div>
        <p>{{ $message }}</p>
        <p>Password: {{ $password }}!</p>

        {{-- <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['price'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total:</td>
                    <td>{{ $total }}</td>
                </tr>
            </tfoot>
        </table> --}}

        {{-- <p class="footer">Thank you for shopping with us!</p> --}}

        {{-- <img class="logo" src="https://example.com/logo.png" alt="Company Logo"> --}}
    </div>
</body>

</html>
