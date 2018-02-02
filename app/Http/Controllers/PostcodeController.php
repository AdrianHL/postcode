<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPostcodeRequest;
use App\Http\Resources\PostcodeResource;
use App\Http\Resources\PostcodeResourceCollection;
use App\Postcode;
use App\Exceptions\PostcodeNotFound;

class PostcodeController extends Controller
{
    /**
     * Search postcodes by partial match
     *
     * @param string $pcd
     * @return PostcodeResourceCollection
     */
    public function index(string $pcd)
    {
        $postcodes = Postcode::wherePostcode($pcd)->paginate();
        return new PostcodeResourceCollection($postcodes);
    }

    /**
     * Find postcode by ID
     *
     * @param string $id
     * @return PostcodeResource
     * @throws PostcodeNotFound
     */
    public function show(string $id)
    {
        $postcode = Postcode::find($id);

        if (empty($postcode)) {
            throw new PostcodeNotFound;
        }

        return new PostcodeResource($postcode);
    }

    /**
     * Search postcodes by LAT and LONG within a maximum distance (default to 0.25)
     *
     * @param SearchPostcodeRequest $request
     * @return PostcodeResourceCollection
     */
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
