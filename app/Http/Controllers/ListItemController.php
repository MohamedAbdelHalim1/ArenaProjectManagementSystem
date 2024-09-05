<?php

namespace App\Http\Controllers;

use App\Models\ListItem;
use App\Models\Task;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    public function index(Task $task)
    {
        return $task->listItems;
    }

    public function store(Request $request, Task $task)
    {
        $listItem = $task->listItems()->create([
            'text' => $request->input('text'),
        ]);

        return response()->json($listItem);
    }

    public function update(Request $request, ListItem $listItem)
    {
        $listItem->update([
            'is_checked' => $request->input('is_checked')
        ]);

        return response()->json($listItem);
    }
}
