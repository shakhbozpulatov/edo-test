<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Organization;
use App\Models\Region;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function tree(Request $request)
    {
        $query = Organization::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($request->filled('region_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('region_id')
                  ->orWhere('region_id', $request->region_id);
            });
        }

        if ($request->filled('district_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('district_id')
                  ->orWhere('district_id', $request->district_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', "%{$search}%");
        }

        $roots = $query->get();

        return response()->json($this->buildTree($roots, $request->input('selected', [])));
    }

    private function buildTree($nodes, array $selected): array
    {
        return $nodes->map(function ($org) use ($selected) {
            return [
                'id'          => $org->id,
                'name'        => $org->name,
                'type'        => $org->type,
                'is_category' => $org->is_category,
                'checked'     => in_array($org->id, $selected),
                'children'    => $this->buildTree($org->children, $selected),
            ];
        })->values()->toArray();
    }

    public function regions()
    {
        return response()->json(Region::orderBy('name')->get(['id', 'name']));
    }

    public function districts(Region $region)
    {
        return response()->json($region->districts()->orderBy('name')->get(['id', 'name']));
    }
}
