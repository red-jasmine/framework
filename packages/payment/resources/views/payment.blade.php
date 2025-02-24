<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聚合支付中心 - 发起支付</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/mhDoLbDldZc3qpsJHpLogda//BVZbgYuw6kof4u2FrCedxOtgRZDTHgHUhOCVim"
            crossorigin="anonymous"></script>

    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .payment-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">支付中心</h2>
    <form class="payment-form" method="POST" action="{{route('payment.payer.trades.pay')}}">
        <div class="form-group">
            <label for="paymentOrder">支付单号</label>
            <input type="text" class="form-control"
                   value="{{$trade->tradeNo}}"
                   id="TRADE" name="trade_no" placeholder="支付单号" readonly>
        </div>
        <div class="form-group">
            <label for="amount">支付金额</label>
            <input type="number" class="form-control" id="amount" name="amount"
                   value="{{$trade->amount->format()}}"
                   placeholder="支付金额" readonly>
        </div>
        <div class="form-group">
            <label for="paymentMethod">支付方式</label>
            <select class="form-select" id="paymentMethod" name="method">
                @foreach($trade->methods??[] as $method)
                    <option value="{{$method->code}}">{{$method->name}}</option>
                @endforeach


            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">发起支付</button>
    </form>
</div>




</body>
</html>
