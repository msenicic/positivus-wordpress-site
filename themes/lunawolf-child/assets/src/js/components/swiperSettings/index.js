import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";

export default () => {
  const sponsors_swiper = new Swiper(".sponsors__swiper", {
    slidesPerView: 1,
    grabCursor: true,
    breakpoints: {
      390: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 6,
      },
    }
  });

  const studies_swiper = new Swiper(".studies__swiper", {
    slidesPerView: "auto",
    grabCursor: true,
    spaceBetween: 20,
    breakpoints: {
      1024: {
        spaceBetween: 0,
        slidesPerView: 3,
      },
    }
  });

  const testimonials_swiper = new Swiper(".testimonials__swiper", {
    modules: [Navigation, Pagination],
    slidesPerView: "auto",
    centeredSlides: true,
    grabCursor: true,
    spaceBetween: 30,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      1024: {
        spaceBetween: 50,
      },
    }
  });
}