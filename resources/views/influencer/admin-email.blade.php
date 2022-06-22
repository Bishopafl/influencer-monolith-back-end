<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Influencer</title>
</head>
<body>
    <h1>{{ $orderdetails['title']}}</h1>
    <p>{{ $orderdetails['description']}}</p>

    <p>Order #{{$order->id}} with a total of ${{$order->admin_total}} has been completed!</p>
</body>
</html>
