@extends('layouts.app')

@section('title', 'Curriculum In Preparation - ' . $course->title)

@section('content')
<div class="py-20 text-center max-w-md mx-auto px-4">
    <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl">
        <i class="fa-solid fa-hourglass-half"></i>
    </div>
    <h2 class="text-xl font-bold text-slate-900 mb-2">Curriculum Under Preparation</h2>
    <p class="text-xs text-slate-500 mb-6">
        The instructor is actively structuring lessons and code labs for <strong>{{ $course->title }}</strong>. Check back shortly!
    </p>
    <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
        Return to My Dashboard
    </a>
</div>
@endsection
