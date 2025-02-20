<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="2">Client Name</td>
            <td colspan="2">Jethro, Thwala</td>
            <td>Region</td>
            <td>Water Meter</td>
        </tr>
        <tr>
            <td>Client ID</td>
            <td>689.000</td>
            <td>House Number</td>
            <td>Electricity Meter</td>
            <td>Email</td>
            <td>Order No</td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="5">BillData</td>
        </tr>
        <tr>
            <td>House No</td>
            <td>Jethro, Thwala</td>
            <td>Bill Month</td>
            <td>July</td>
            <td>Payment Due Date</td>
            <td>10-Aug-2023</td>
        </tr>
        <tr>
            <td>Country</td>
            <td>Slovokia</td>
            <td>Start Date</td>
            <td>01-Jul-2023</td>
            <td>End Date</td>
            <td>31-Jul-2023</td>
        </tr>
        <!-- The rest of the rows can be added in a similar fashion -->
    </table>
</body>
</html>
