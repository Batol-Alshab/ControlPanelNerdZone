<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getMaterials($id)
    {

        $user = Auth::guard(name: 'sanctum')->user();

        if (! $user) {
            $materials = Section::find($id)->materials
                ->map(fn($material) => [
                    'id'    => $material->id,
                    'name'  => $material->name,
                    'image' => asset('storage/' . $material->image),
                ]);
            return $this->successResponse($materials);
        } else {
            $materials = $user->materials->map(fn($material) => [
                'id'    => $material->id,
                'name'  => $material->name,
                'rate'  => $material->pivot->rate,
                'image' => asset('storage/' . $material->image),
            ]);
            return $this->successResponse($materials);
        }
    }
}
