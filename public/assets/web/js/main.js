//  Slider Example

$(".property-slider-wrapper").slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  infinite: true,
  arrows: false,
  dots: false,
  prevArrow: $(".property-button-prev"),
  nextArrow: $(".property-button-next"),
  autoplay: true,
  responsive: [
    {
      breakpoint: 1440,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 1210,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },

    {
      breakpoint: 1054,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 778,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
  ],
});
$(".thumbnail-slider-wrapper").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  infinite: true,
  arrows: false,
  dots: false,
  prevArrow: $(".property-button-prev"),
  nextArrow: $(".property-button-next"),
  autoplay: true,
  responsive: [
    {
      breakpoint: 1440,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 1210,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },

    {
      breakpoint: 1054,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 778,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
  ],
});

$(".property-button-prev").click(function () {
  $(".property-slider-wrapper").slick("slickPrev")
})

$(".property-button-next").click(function () {
  $(".property-slider-wrapper").slick("slickNext")
})


$(".loop-slider-wraper").slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 0,
  speed: 10000,
  cssEase: "linear",
  arrows: false,
  dots: false,
  responsive: [
    {
      breakpoint: 1480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 762,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 550,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
  ],
});

$(".testimonial-slider").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  infinite: true,
  prevArrow: $(".testimonial-prev-arrow"),
  nextArrow: $(".testimonial-next-arrow"),
  dots: false,
  autoplay: true,
  // responsive: [
  //   {
  //     breakpoint: 1440,
  //     settings: {
  //       slidesToShow: 3,
  //       slidesToScroll: 1,
  //     },
  //   },
  //   {
  //     breakpoint: 1024,
  //     settings: {
  //       slidesToShow: 2,
  //       slidesToScroll: 1,
  //     },
  //   },
  //   {
  //     breakpoint: 768,
  //     settings: {
  //       slidesToShow: 1,
  //       slidesToScroll: 1,
  //     },
  //   },
  // ],
});

$(".blog-slider").slick({
  slidesToShow: 3,
  // slidesToScroll: 1,
  infinite: true,
  arrows: false,
  dots: false,
  autoplay: false,
  responsive: [
    {
      breakpoint: 1041,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 778,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
  ],
});
$(".floor-nav-link").click(function () {
    const index = $(this).data("tab"); // ← safer than index()

    $(".floor-nav-link").removeClass("active");
    $(this).addClass("active");

    $(".floor-tab-content").removeClass("active").hide();
    $(".floor-tab-content").eq(index).addClass("active").fadeIn();
});

// $(".floor-nav-link").click(function () {
//     const index = $(this).index();

//     // Buttons active state
//     $(".floor-nav-link").removeClass("active");
//     $(this).addClass("active");

//     // Show corresponding content
//     $(".floor-tab-content").removeClass("active").hide();
//     $(".floor-tab-content").eq(index).addClass("active").fadeIn();
// });


// $(".floor-nav-link").click(function () {
//   $(".floor-nav-link").each(function () {
//     $(this).removeClass("active")
//   })
//   $(this).addClass("active")
// })

// let tabBtn = document.querySelectorAll(".nav-link");

$(document).ready(function () {
  $(".nav-link").on("click", function () {
    $(".property-slider-wrapper").slick("setPosition");
  });
});

// document.addEventListener("DOMContentLoaded", function () {
//   const links = document.querySelectorAll(".nav-links li a");
//   const path = window.location.pathname;
//   const currentPage = path === "/" ? "index.php" : path.split("/").pop();

//   links.forEach((link) => {
//     const href = link.getAttribute("href");
//     if (
//       href === currentPage ||
//       (href === "index.php" && currentPage === "index.php")
//     ) {
//       link.classList.add("active");
//     } else {
//       link.classList.remove("active");
//     }
//   });
// });

/////////////////////////////

$(document).ready(function () {
  $("#menu-btn").click(function () {
    $("#header-offcanva").toggleClass("active-sidebar", "2000");
  });
});

document.getElementById("menu-btn").addEventListener("click", function () {
  let icon = this.querySelector("i");
  if (icon.classList.contains("fa-bars")) {
    icon.classList.replace("fa-bars", "fa-xmark");
  } else {
    icon.classList.replace("fa-xmark", "fa-bars");
  }
});

// const heartBtn = document.querySelectorAll(".heart-save-btn");

// heartBtn.forEach((saveBtn) => {
//   saveBtn.addEventListener("click", () => {
//     const icon = saveBtn.querySelector("i");
//     if (icon.classList.contains("fa-regular")) {
//       icon.classList.replace("fa-regular", "fa-solid");
//     } else {
//       icon.classList.replace("fa-solid", "fa-regular");
//     }
//   });
// });


const thumbnails = document.querySelectorAll(".thumbnail img");
const mainImage = document.getElementById("mainImage");

let currentIndex = 0;

function updateSlider(index) {
  thumbnails.forEach((thumb, i) => {
    thumb.parentElement.classList.toggle("active", i === index);
  });
  const newSrc = thumbnails[index].getAttribute("data-large");
  mainImage.src = newSrc;
  currentIndex = index;
}

thumbnails.forEach((thumb, index) => {
  thumb.addEventListener("click", () => {
    updateSlider(index);
  });
});

document.getElementById("prevBtn")?.addEventListener("click", () => {
  const newIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
  updateSlider(newIndex);
});

document.getElementById("nextBtn")?.addEventListener("click", () => {
  const newIndex = (currentIndex + 1) % thumbnails.length;
  updateSlider(newIndex);
});
















