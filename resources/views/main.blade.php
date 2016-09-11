<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    {!! Form::open(['url' => 'calculate']) !!}
        {{ Form::bsText('Сумма кредита', $Сумма_кредита) }}
        {{ Form::bsText('Срок выплаты лет', $Срок_выплаты_лет) }}
        {{ Form::bsText('Процентная ставка', $Процентная_ставка) }}
        {{ Form::bsText('Первый платеж', $Первый_платеж) }}

    @if($Срок_выплаты_лет)
        @for($i = 1; $i <= $Срок_выплаты_лет; $i++)
            <?php $varValue = isset($years[$i]) ? $years[$i] : null; ?>
        {{ Form::bsText("год[$i]", $varValue) }}
        @endfor
    @endif


        <button type="submit" class="btn btn-default">Рассчитать</button>
    {!! Form::close() !!}


    @if(isset($overPayment))
        <p>Переплата: <b>{{$overPayment}}</b></p>
    @endif

    @if(isset($bodyPayment))
        <p>Плата по кредиту: <b>{{$bodyPayment}}</b></p>
    @endif

    @if(isset($overPayment) and $bodyPayment)
        <p>Итого: <b>{{$overPayment + $bodyPayment}}</b></p>
    @endif

    @if($headers)
        <h2>Расчёты</h2>
        @foreach($periods as $key => $period)
            <p>Расчёты за год {{$key}}</p>
            <table class="table table-hover">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <td>{{$header}}</td>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($period as $item)
                    <tr>
                        @foreach($item as $field)
                            <td>{{$field}}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
</div>

</body>
</html>

