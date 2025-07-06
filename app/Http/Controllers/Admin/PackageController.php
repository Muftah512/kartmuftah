<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * ⁄—÷ ﬁ«∆„… «·»«ﬁ« .
     */
    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * ≈ŸÂ«— ‰„Ê–Ã ≈‰‘«¡ »«ﬁ… ÃœÌœ….
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Õ›Ÿ «·»«ﬁ… «·ÃœÌœ… ›Ì «·ﬁ«⁄œ….
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'size_mb'       => 'required|integer|min:1',
            'mikrotik_profile' => 'nullable|string',
        ]);

        Package::create($data);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', ' „ ≈÷«›… «·»«ﬁ… »‰Ã«Õ.');
    }

    /**
     * ≈ŸÂ«— ‰„Ê–Ã  ⁄œÌ· »«ﬁ… „ÊÃÊœ….
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     *  ÕœÌÀ »Ì«‰«  «·»«ﬁ….
     */
    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'size_mb'       => 'required|integer|min:1',
            'mikrotik_profile' => 'nullable|string',
        ]);

        $package->update($data);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', ' „  ÕœÌÀ «·»«ﬁ… »‰Ã«Õ.');
    }

    /**
     * Õ–› »«ﬁ….
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', ' „ Õ–› «·»«ﬁ….');
    }
}
