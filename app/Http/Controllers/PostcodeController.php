<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostcodeResource;
use App\Http\Resources\PostcodeResourceCollection;
use App\Postcode;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
            throw new ResourceNotFoundException("Postcode not found!");
        }

        return new PostcodeResource($postcode);
    }
}
