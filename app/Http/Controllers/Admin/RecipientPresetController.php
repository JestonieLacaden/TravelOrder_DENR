<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemorandumRecipientPreset;
use Illuminate\Http\Request;

class RecipientPresetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presets = MemorandumRecipientPreset::orderBy('display_order')->paginate(15);
        return view('admin.recipient_presets.index', compact('presets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.recipient_presets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        MemorandumRecipientPreset::create($validated);

        return redirect()->route('admin.recipient-presets.index')
            ->with('success', 'Recipient preset created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemorandumRecipientPreset $recipientPreset)
    {
        return view('admin.recipient_presets.edit', compact('recipientPreset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemorandumRecipientPreset $recipientPreset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $recipientPreset->update($validated);

        return redirect()->route('admin.recipient-presets.index')
            ->with('success', 'Recipient preset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemorandumRecipientPreset $recipientPreset)
    {
        $recipientPreset->delete();

        return redirect()->route('admin.recipient-presets.index')
            ->with('success', 'Recipient preset deleted successfully.');
    }
}
