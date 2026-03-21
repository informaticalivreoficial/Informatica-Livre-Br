@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Termos e Condições</h1>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            {!!$configuracoes->terms_condicions!!}
        </div>
    </section>
   
@endsection