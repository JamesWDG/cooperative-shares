@extends('layouts.web.app')

@php
    $page    = $page ?? false;
    $content = $content ?? '';
@endphp

@section('section')
    <x-hero-banner Heading="Home" subHeading="Terms & Conditions" :route="route('index')" />

    <section class="terms-sec">
        <div class="container">
            @if(!empty($content))
                <div class="terms-content">
                    {!! $content !!} {{-- full HTML from CMS --}}
                </div>
            @endif
        </div>
    </section>
@endsection
