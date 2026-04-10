<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Location\CreateLocationRequest;
use App\Interfaces\Http\Requests\Location\UpdateLocationRequest;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService) {}

    public function index()
    {
        return view('locations.index');
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(CreateLocationRequest $request)
    {
        $dto = $request->toDto();
        $this->locationService->create($dto);

        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(int $id)
    {
        $location = $this->locationService->findById($id);

        return view('locations.edit', compact('location'));
    }

    public function update(int $id, UpdateLocationRequest $request)
    {
        $dto = $request->toDto();
        $this->locationService->update($dto, $id);

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->locationService->destroy($id);

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}
