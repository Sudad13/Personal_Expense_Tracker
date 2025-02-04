<?php



namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = auth()->user()->expenses()->orderBy('date', 'desc')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category' => 'required|string|max:255',
        ]);

        auth()->user()->expenses()->create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
{
    // Ensure the user can only edit their own expenses
    if ($expense->user_id !== auth()->id()) {
        abort(403);
    }
    
    return view('expenses.edit', compact('expense'));
}

public function update(Request $request, Expense $expense)
{
    // Ensure the user can only update their own expenses
    if ($expense->user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'category' => 'required|string|max:255',
    ]);

    $expense->update($validated);

    return redirect()->route('expenses.index')
        ->with('success', 'Expense updated successfully!');
}

public function destroy(Expense $expense)
{
    // Ensure the user can only delete their own expenses
    if ($expense->user_id !== auth()->id()) {
        abort(403);
    }

    $expense->delete();

    return redirect()->route('expenses.index')
        ->with('success', 'Expense deleted successfully!');
}
    // Add other methods (edit, update, delete) as needed
}