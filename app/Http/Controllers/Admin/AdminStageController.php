<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stage;

class AdminStageController extends Controller
{
    public function index()
    {
        $stages = Stage::orderBy('display_order')->get();
        return view('admin.stages.index', compact('stages'));
    }

    public function create()
    {
        return view('admin.stages.create');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
         'slug' => 'required|string|max:255|unique:stages,slug',
        'display_order' => 'required|integer|min:1',
    ]);

    Stage::where('display_order', '>=', $validated['display_order'])
        ->increment('display_order');

    Stage::create($validated);

   return redirect()->route('admin.stages.index');}

    public function edit(Stage $stage)
    {
        return view('admin.stages.edit', compact('stage'));
    }

   public function update(Request $request, Stage $stage)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'display_order' => 'required|integer|min:1',
    ]);

    $newOrder = $validated['display_order'];
    $oldOrder = $stage->display_order;

    if ($newOrder != $oldOrder) {
        if ($newOrder < $oldOrder) {
            // Stage is moving up → push others down
            Stage::where('display_order', '>=', $newOrder)
                ->where('display_order', '<', $oldOrder)
                ->increment('display_order');
        } else {
            // Stage is moving down → pull others up
            Stage::where('display_order', '<=', $newOrder)
                ->where('display_order', '>', $oldOrder)
                ->decrement('display_order');
        }
    }

    $stage->update($validated);

   return redirect()->route('admin.stages.index')
    ->with('status', 'Stage updated and reordered successfully!');
}
public function reorder(Request $request)
{
    $request->validate([
        'order' => 'required|array',
        'order.*' => 'integer|exists:stages,id',
    ]);

    foreach ($request->order as $index => $stageId) {
        \App\Models\Stage::where('id', $stageId)
            ->update(['display_order' => $index + 1]);
    }

    return response()->json(['status' => 'success']);
}
public function destroy(Stage $stage)
{
    // Shift down orders of stages that come after this one
    Stage::where('display_order', '>', $stage->display_order)
        ->decrement('display_order');

    $stage->delete();

    return redirect()->route('admin.stages.index')
        ->with('status', 'Stage deleted successfully and order updated!');
}

}
