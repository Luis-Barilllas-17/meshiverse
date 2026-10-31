<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDishOfTheDayRequest;
use App\Models\DishOfTheDaySetting;
use App\Models\Recipe;
use App\Support\DishOfTheDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DishOfTheDayController extends Controller
{
    public function index(DishOfTheDay $dishOfTheDay): View
    {
        return view('admin.dish-of-the-day', [
            'setting' => DishOfTheDaySetting::query()->firstOrCreate([], ['mode' => 'automatic']),
            'recipes' => Recipe::query()->with('animeSeries')->orderBy('title')->get(),
            'dishOfTheDay' => $dishOfTheDay->get(),
        ]);
    }

    public function update(UpdateDishOfTheDayRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['mode'] === 'manual') {
            Recipe::query()->findOrFail($data['recipe_id'])->update(['published_at' => now()]);
        } else {
            $data['recipe_id'] = null;
        }

        DishOfTheDaySetting::query()->firstOrCreate()->update([
            ...$data,
            'updated_by' => $request->user()?->getKey(),
        ]);

        return to_route('admin.dish.index')->with('success', 'La configuración del platillo del día fue actualizada.');
    }
}
