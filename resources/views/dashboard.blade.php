<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Quick Stats -->
                    <div class="bg-blue-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Total Expenses</h3>
                        <p class="text-2xl font-bold">
                            ${{ auth()->user()->expenses()->sum('amount') }}
                        </p>
                    </div>
                    
                    <div class="bg-green-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">This Month</h3>
                        <p class="text-2xl font-bold">
                            ${{ auth()->user()->expenses()
                                ->whereMonth('date', now()->month)
                                ->sum('amount') }}
                        </p>
                    </div>

                    <div class="bg-purple-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Total Categories</h3>
                        <p class="text-2xl font-bold">
                            {{ auth()->user()->expenses()->distinct('category')->count('category') }}
                        </p>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Expenses</h3>
                        <a href="{{ route('expenses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Expense
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Category</th>
                                    <th class="px-4 py-2 text-right">Amount</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(auth()->user()->expenses()->latest()->take(5)->get() as $expense)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $expense->date }}</td>
                                        <td class="px-4 py-2">{{ $expense->description }}</td>
                                        <td class="px-4 py-2">{{ $expense->category }}</td>
                                        <td class="px-4 py-2 text-right">${{ number_format($expense->amount, 2) }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center">No expenses found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-right">
                        <a href="{{ route('expenses.index') }}" class="text-blue-500 hover:text-blue-700">View All Expenses →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>