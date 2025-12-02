@extends('layouts.web.app')

@php
    $page = false;
@endphp

@section('section')
    <x-hero-banner Heading="Home > Services" subHeading="Service Detail" :route="route('services')" />
    <section class="ser-img-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img src="{{ asset('assets/web/images/detail-main.png') }}" alt="" class="img-fluid detail-main-img">
                </div>
            </div>
        </div>
    </section>
    <section class="ser-detail-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="sec-hd">Service Details</h2>
                    <p class="para">Donec imperdiet neque vel mattis suscipit. Duis diam metus, fermentum ac varius nec,
                        auctor vitae lorem. Aliquam lacinia quam sed viverra bibendum. Nunc lectus sem, posuere sed
                        scelerisque eu, cursus quis risus. Aenean laoreet tempus velit id interdum. Pellentesque vel nisi
                        non diam ultrices ullamcorper et pharetra felis. Maecenas eget nunc mollis, vestibulum felis quis,
                        consequat sem. Integer vitae bibendum velit. Nunc mi eros, pulvinar ac risus vel, consequat sagittis
                        lectus. Cras eget tincidunt turpis. Sed condimentum at massa sed vestibulum. Phasellus sagittis
                        aliquam magna, sit amet sodales lorem bibendum id. Etiam pharetra, leo at luctus venenatis, libero
                        lacus commodo odio, nec laoreet nisl nulla quis tellus. Interdum et malesuada fames ac ante ipsum
                        primis in faucibus. Sed scelerisque porttitor lectus eget mattis. <br><br>Nunc tincidunt egestas
                        velit nec efficitur. Duis vestibulum volutpat nibh sed consequat. Maecenas tincidunt, arcu vel
                        iaculis cursus, odio tellus consectetur diam, vitae aliquet ligula leo ut tortor. Aliquam et purus
                        pulvinar enim vulputate placerat varius vitae justo. Aliquam erat volutpat.Donec dignissim neque
                        purus, vel suscipit velit gravida quis. Cras lobortis, neque sed facilisis fringilla, enim neque
                        suscipit neque, vitae porta lacus eros feugiat massa. Phasellus efficitur, ex et consequat
                        elementum, dolor neque pharetra purus, vitae faucibus risus dolor bibendum leo. Quisque volutpat
                        odio id egestas suscipit.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="brilliants-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="sec-hd">The brilliants in terms</h2>
                    <ul class="p-0">
                        <li><i class="fa-solid fa-circle"></i> Donec imperdiet neque vel mattis suscipit. Duis diam metus
                        </li>
                        <li><i class="fa-solid fa-circle"></i> Fermentum ac varius nec, auctor vitae lorem. Aliquam lacinia
                            quam</li>
                        <li><i class="fa-solid fa-circle"></i> Sed viverra bibendum. Nunc lectus sem, posuere sed</li>
                        <li><i class="fa-solid fa-circle"></i> Hcelerisque eu, cursus quis risus. Aenean laoreet tempus
                            velit id</li>
                        <li><i class="fa-solid fa-circle"></i> Interdum. Pellentesque vel nisi non diam ultrices ullamcorper
                        </li>
                        <li><i class="fa-solid fa-circle"></i> Et pharetra felis. Maecenas eget nunc mollis, vestibulum
                            felis quis,</li>
                        <li><i class="fa-solid fa-circle"></i> Consequat sem. Integer vitae bibendum velit. Nunc mi eros,
                            pulvinar</li>
                        <li><i class="fa-solid fa-circle"></i> Ac risus vel, consequat sagittis lectus. Cras eget tincidunt
                            turpis.</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('assets/web/images/b1-img.png') }}" alt="" class="img-fluid briants-img">
                </div>
            </div>
        </div>
    </section>
    <x-partner-section />
@endsection
