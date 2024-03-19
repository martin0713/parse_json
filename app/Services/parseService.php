<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class ParseService
{
    private function getData(): string
    {
        return file_get_contents(storage_path('raileurope_original.json'));
    }
    public function all(): string
    {
        $json = $this->getData();
        return $json;
    }

    public function offers(): array //\Illuminate\Support\Collection
    {
        $json = json_decode($this->getData());
        $legSolutions = collect($json->legs[0]->solutions);
        $products = collect($json->products);
        $offers = collect($json->offers);
        $rows = $offers->map(function ($offer) use ($legSolutions, $products) {
            $legSolution = $legSolutions->firstWhere('id', $offer->legSolution);
            $segment = data_get($legSolution, 'segments.0');
            $vehicleCode = data_get($segment, 'vehicle.code');
            $vehicleRef = data_get($segment, 'vehicle.reference');
            $depCode = data_get($legSolution, 'origin.label');
            $arrCode = data_get($legSolution, 'destination.label');
            $depTime = Carbon::parse($legSolution->departure)->format('Ymd H:m');
            $arrTime = Carbon::parse($legSolution->departure)->format('Ymd H:m');
            $fare = data_get($offer, 'fareOffers.0.fares.0');
            $travelers = implode('', $fare->travelers);
            $price = data_get($offer, 'fareOffers.0.prices.billings.0.billingPrice.amount.value');

            return [
                'offerId' => $offer->id,
                'legId' => $legSolution->id,
                'depCode' => $depCode,
                'arrCode' => $arrCode,
                'departure' => $depTime,
                'arrival' => $arrTime,
                'segmentIds' => data_get($legSolution, 'segments.0.id'),
                'segments' => $vehicleCode . $vehicleRef . $depCode . $depTime . '->' . $arrTime . $arrCode,
                'travelerProduct' => $travelers . ':' . $fare->productCode,
                'travelerPrice' => $travelers . ':' . $price,
                'category' => data_get($offer, 'comfortCategory.label'),
                'flexibility' => $products->firstWhere('code', $fare->productCode)->label,
                'kkdayFareList' => 'ALL:' . $price
            ];
        });
        return $rows->all();
    }
}
