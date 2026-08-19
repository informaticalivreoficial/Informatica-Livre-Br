@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 text-center text-white relative">
            <span class="inline-block bg-white/10 text-teal-100 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4 backdrop-blur">Legal</span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Termos e Condições</h1>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 border border-gray-100 prose prose-gray max-w-none text-gray-700 leading-relaxed">
                {!! $configuracoes->terms_condicions !!}
            </div>
        </div>
    </section>

@endsection