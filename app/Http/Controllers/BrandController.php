<?php

namespace App\Http\Controllers;

use App\Services\RankService;
use Domain\Catalog\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $rankService;//для обновления rank из муншайн

    public function __construct(RankService $rankService)
    {
        $this->rankService = $rankService;
    }

    public function reorder(Request $request) {
        $this->rankService->updateRanks(new Brand(), $request->data);

        return response()->json(['message' => 'Ranks updated successfully']);
    }
}
