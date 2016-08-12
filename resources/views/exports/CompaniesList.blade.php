<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <tr>
            <th align="center" colspan="2">
                List of Companies
            </th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Address Line 1</th>
            <th>Address Line 2</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Discount</th>
            <th>Contacts</th>
        </tr>
        @foreach( $companies as $company )
        <tr>
            <td>{{ $company['name'] }}</td>
            <td>{{ $company['address_line_1'] }}</td>
            <td>{{ $company['address_line_2'] }}</td>
            <td>{{ $company['phone'] }}</td>
            <td>{{ $company['email'] }}</td>
            <td>{{ $company['discount'] }}</td>
            <td>
                @foreach( $company['contacts'] as $contact )
                    {{ $contact['name'] }}&nbsp;&lt;{{ $contact['email'] }}&gt;&nbsp;{{ $contact['phone'] }},
                @endforeach
            </td>
        @endforeach
    </table>
</body>
</html>
