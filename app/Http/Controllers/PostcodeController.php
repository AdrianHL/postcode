<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPostcodeRequest;
use App\Http\Resources\PostcodeResource;
use App\Http\Resources\PostcodeResourceCollection;
use App\Postcode;
use App\Exceptions\PostcodeNotFound;

class PostcodeController extends Controller
{
    public function index($pcd)
    {
        $postcodes = Postcode::wherePostcode($pcd)->paginate();
        return new PostcodeResourceCollection($postcodes);
    }

    public function show($id)
    {
        $postcode = Postcode::find($id);

        if (empty($postcode)) {
            throw new PostcodeNotFound;
        }

        return new PostcodeResource($postcode);
    }

    public function search(SearchPostcodeRequest $request)
    {
        $postcodes = Postcode::whereClosest(
            $request->get('lat'),
            $request->get('long'),
            $request->get('dt', $defaultDistance = 0.25)
        )->paginate();

        return new PostcodeResourceCollection($postcodes);
    }
}
