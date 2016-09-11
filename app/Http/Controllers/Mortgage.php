<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class Mortgage extends Controller
{
    public function index(){
        return view('main', [
            'headers' => '',
            'Сумма_кредита' => '',
            'Срок_выплаты_лет' => '',
            'Процентная_ставка' => '',
            'Первый_платеж' => '',
        ]);
    }

    public function calculate(Request $request){
        $years = $request->get('год');
        $creditSum = $request->get('Сумма_кредита');
        $creditPeriodYears = $request->get('Срок_выплаты_лет');
        $creditPercent = $request->get('Процентная_ставка');
        $firstPayment = $request->get('Первый_платеж');

        //Сумма общего платежа
//        $commonPayment = 100000;

        //Тело кредита за 1 период
//        $periodCreditPart = $creditSum / $creditPeriodYears / 12;

        $creditPeriodsCount = $creditPeriodYears * 12;

        $periods = [];
        $yearIndex = 1;
        $monthNumber = 1;
//        dd($years);
        for($i = 1; $i < $creditPeriodsCount; $i++){

            if($monthNumber > 12) {
                $monthNumber = 1;
                $yearIndex++;
            };

            //Количество дней в расчётном периоде
            $daysInMonth = Carbon::now()->month($monthNumber)->daysInMonth;


            //Общий платеж
            $commonPayment = $years[$yearIndex];
            if($i == 1) $commonPayment = $firstPayment;

            //Набежавшие проценты
            $percents = $creditSum * ($creditPercent / 100) * $daysInMonth / 365;
            if(($creditSum + $percents) <= 0) continue;
            //Сумма платежа
            if(($creditSum + $percents) < $commonPayment){
                $commonPayment = $creditSum + $percents;
            }

            $period = [
                'Период' => $i,
                'Общий платёж' => round($commonPayment),
                'Погашение тела кредита' => round($commonPayment - $percents),
                'Погашено процентов' => round($percents),
                'Остаток основной суммы' => round($creditSum),
                'Начислено процентов' => round($percents),
                'Год' => $yearIndex,
                'Месяц' => $monthNumber,

            ];

            //Остаток основной суммы
            $creditSum = $creditSum - $commonPayment + $percents;

            $periods[] = $period;
            $monthNumber++;
        }

        //Шапка таблицы
        $headers = array_keys($periods[0]);

        $periodsCollection = collect($periods);
        $yearedPeriods = $periodsCollection->groupBy('Год');
//        dd($yearedPeriods);

        $params = array_merge([
            'headers' => $headers,
            'periods' => $yearedPeriods,
            'years' => $years,
            'overPayment' => $periodsCollection->sum('Начислено процентов'),
            'bodyPayment' => $periodsCollection->sum('Погашение тела кредита')
        ], $request->toArray());


        return view('main', $params);
    }
}
