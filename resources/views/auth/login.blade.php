@extends('layouts.app')

@section('content')
<div class="max-w-sm mx-auto bg-white p-8 mt-12 shadow-lg rounded-lg">
    <h2 class="text-center text-2xl font-semibold mb-4">Login Admin</h2>

    <!-- Menampilkan error jika ada -->
    @if ($errors->any())
        <div class="mb-4 text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form login -->
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-4">
            <label for="id" class="block">NPM/NIK</label>
            <input type="text" name="id" id="id" class="w-full border rounded-lg p-2 mt-1" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="block">Password</label>
            <input type="password" name="password" id="password" class="w-full border rounded-lg p-2 mt-1" required>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
            Login
        </button>
    </form>
</div>
@endsection
