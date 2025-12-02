@extends('layouts.user.app')

@section('section')
    <section class="main-content-area">
        <h1 class="dashboard-hd">Save Listings</h1>
        <div class="profile-info-wrapper listing-info">
            <div class="co-op-wrapper">
                <div class="blog-card">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{ asset('assets/user/images/coop.png') }}" alt="" class="img-fluid">
                            <div class="date">Jan 28, 2025</div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="blog-card">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{asset('assets/user/images/coop2.png') }}" alt="" class="img-fluid">
                            <div class="date">Apr 17, 2025 </div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="blog-card">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{asset('assets/user/images/coop3.png') }}" alt="" class="img-fluid">
                            <div class="date">Feb 14, 2025</div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="blog-card blog-card2">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{asset('assets/user/images/coop4.png') }}" alt="" class="img-fluid">
                            <div class="date">Jan 28, 2025</div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="blog-card blog-card2">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{asset('assets/user/images/coop5.png') }}" alt="" class="img-fluid">
                            <div class="date">Apr 17, 2025</div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="blog-card blog-card2 blog-card3">
                    <div class="all-area">
                        <div class="blog-img-area">
                            <img src="{{asset('assets/user/images/coop6.png') }}" alt="" class="img-fluid">
                            <div class="date">Feb 14, 2025</div>
                        </div>
                        <div class="blog-content-area">
                            <h4>Lorem Ipsum is simply <br> dummy text of the printing.</h4>
                            <p>Lorem Ipsum is simply dummy text of the <br> printing and typesetting industry. Lorem
                                Ipsum.
                            </p>
                            <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination-wrapper coop-pagination-wrapper">
                <div class="pag-para">
                    <p>Showing <span>1</span> of <span>6</span> Results</p>
                </div>
                <div class="pagination-btns">
                    <button>
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

