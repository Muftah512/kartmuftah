<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * ��� ����� �������.
     */
    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * ����� ����� ����� ���� �����.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * ��� ������ ������� �� �������.
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
            ->with('success', '�� ����� ������ �����.');
    }

    /**
     * ����� ����� ����� ���� ������.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * ����� ������ ������.
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
            ->with('success', '�� ����� ������ �����.');
    }

    /**
     * ��� ����.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', '�� ��� ������.');
    }
}
