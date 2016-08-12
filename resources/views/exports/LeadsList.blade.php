<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <tr>
            <th align="center" colspan="2">
                List of Leads
            </th>
        </tr>
        <tr>
            <th>Given Name</th>
            <th>Family Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Device</th>
            <th>Issue</th>
            <th>Price</th>
        </tr>
        @foreach( $leads as $lead )
        <tr>
            <td>{{ $lead['given_name'] }}</td>
            <td>{{ $lead['family_name'] }}</td>
            <td>{{ $lead['phone'] }}</td>
            <td>{{ $lead['email'] }}</td>
            <td>{{ $lead['device'] }}</td>
            <td>{{ $lead['issue'] }}</td>
            <td>{{ $lead['price'] }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
