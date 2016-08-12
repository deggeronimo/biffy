<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <tr>
            <th align="center" colspan="2">
                List of Customers
            </th>
        </tr>
        <tr>
            <th>Given Name</th>
            <th>Family Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address Line 1</th>
            <th>Address Line 2</th>
            <th>City</th>
            <th>State</th>
            <th>Postal Code</th>
            <th>Country</th>
            <th>Referral Source</th>
            <th>Store Name</th>
        </tr>
        @foreach( $customers as $customer )
        <tr>
            <td>{{ $customer['given_name'] }}</td>
            <td>{{ $customer['family_name'] }}</td>
            <td>{{ $customer['phone'] }}</td>
            <td>{{ $customer['email'] }}</td>
            <td>{{ $customer['address_line_1'] }}</td>
            <td>{{ $customer['address_line_2'] }}</td>
            <td>{{ $customer['city'] }}</td>
            <td>{{ $customer['state'] }}</td>
            <td>{{ $customer['postal_code'] }}</td>
            <td>{{ $customer['country'] }}</td>
            <td>{{ $customer['referral_source'] }}</td>
            <td>{{ $customer['store_name'] }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
