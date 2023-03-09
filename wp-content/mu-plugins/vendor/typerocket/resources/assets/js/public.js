// Menu Scripts
const toggleMenu = document.getElementById("wsnavtoggle");
const overlApBlackBg = document.getElementsByClassName("overlapblackbg");
const bodyOverflow = document.querySelector("body");
toggleMenu.addEventListener("click", overflowHidden);
overlApBlackBg[0].addEventListener("click", overflowHidden);

function overflowHidden() {
    if (bodyOverflow.classList.contains("overflow")) {
        bodyOverflow.classList.remove("overflow");
        bodyOverflow.classList.add("none-overflow");
    } else {
        bodyOverflow.classList.remove("none-overflow");
        bodyOverflow.classList.add("overflow");
    }
}

// Swiper Scripts
var sliderSwiper = new Swiper(".sliderSwiper", {

    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoplay: {
        delay: 8000,
        disableOnInteraction: false,
    },

});

var featuresSwiper = new Swiper(".featuresSwiper", {

    slidesPerView: 4,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    navigation: {
        nextEl: ".swiper-button-next-features",
        prevEl: ".swiper-button-prev-features",
    },
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
            // spaceBetween: 20,
        },
        576: {
            slidesPerView: 2,
            // spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            // spaceBetween: 40,
        },
        992: {
            slidesPerView: 4,
            // spaceBetween: 50,
        },
    }

});

var possibilitiesSwiper = new Swiper(".possibilitiesSwiper", {

    slidesPerView: 4,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    // navigation: {
    //   nextEl: ".swiper-button-next-possibilities",
    //   prevEl: ".swiper-button-prev-possibilities",
    // },
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
            // spaceBetween: 20,
        },
        576: {
            slidesPerView: 2,
            // spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            // spaceBetween: 40,
        },
        992: {
            slidesPerView: 4,
            // spaceBetween: 50,
        },
    }

});

var reviewsSwiper = new Swiper(".reviewsSwiper", {

    slidesPerView: 3,
    spaceBetween: 30,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    // navigation: {
    //   nextEl: ".swiper-button-next-possibilities",
    //   prevEl: ".swiper-button-prev-possibilities",
    // },
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
            // spaceBetween: 20,
        },
        576: {
            slidesPerView: 2,
            // spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            // spaceBetween: 40,
        },
        992: {
            slidesPerView: 3,
            // spaceBetween: 50,
        },
    }

});

// Tabs Scripts
function openTab(evt, periodName) {

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    tablinks = document.getElementsByClassName("tablinks");

    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(periodName).style.display = "flex";
    evt.currentTarget.className += " active";

}

// Counter Scripts 
jQuery(document).ready(function ($) {

    $('.count').counterUp({
        delay: 10,
        time: 1000
    });

});
