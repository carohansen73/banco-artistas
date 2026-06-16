<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    public function show(): View
    {
        $routeName = request()->route()->getName();

        $item = collect(config('admin.menu'))
            ->first(fn (array $entry) => ($entry['route'] ?? null) === $routeName);

        return view('admin.coming-soon', [
            'title' => $item['label'] ?? 'Módulo',
        ]);
    }
}
