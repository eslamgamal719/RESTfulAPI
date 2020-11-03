<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }



    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }



    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {

            return $this->successResponse(['data' => $collection], $code); //write data because data does not pass
            // on fractal method that made it by default
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection, $transformer);

        $collection = $this->sortData($collection, $transformer);  //sorting collection

        $collection = $this->paginate($collection);  //sorting collection

        $collection = $this->transformedData($collection, $transformer);  //transforming data returns as an array

        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }




    protected function showOne(Model $model, $code = 200)
    {
        $transformer = $model->transformer;

        $model = $this->transformedData($model, $transformer);

        return $this->successResponse($model, $code);
    }




    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }



    protected function filterData(Collection $collection, $transformer)
    {
        foreach(request()->query() as $index => $value) {
            $attribute = $transformer::originalAttribute($index);

            if(isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }



    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {

            $attribute = $transformer::originalAttribute(request()->sort_by);

            // $collection = $collection->sortBy($attribute);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }



    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();  //current page = 1,2,3,....

        $perPage = 15;

        if(request()->has('per_page')) {
            $perPage = request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();   //slice(offset, steps)

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }




    protected function transformedData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }



    protected function cacheResponse($data)
    {
        $url = request()->url();

        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl =  "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function() use($data) {  //ttl in seconds
            return $data;
        });
    }


}//end of trait
