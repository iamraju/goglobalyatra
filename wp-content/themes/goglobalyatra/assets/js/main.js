const initMobileMenu = () => {
  const menuButton = document.querySelector("[data-menu-button]");
  const mobileMenu = document.querySelector("[data-mobile-menu]");

  if (!menuButton || !mobileMenu) return;

  menuButton.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });
};

const initBannerSlider = () => {
  const slides = Array.from(document.querySelectorAll("[data-slide]"));
  const dots = Array.from(document.querySelectorAll("[data-slide-dot]"));

  if (!slides.length) return;

  let index = 0;

  const showSlide = (i) => {
    slides.forEach((slide, idx) => {
      slide.classList.toggle("hidden", idx !== i);
      slide.classList.toggle("reveal-up", idx === i);
    });

    dots.forEach((dot, idx) => {
      dot.setAttribute("aria-current", idx === i ? "true" : "false");
      dot.classList.toggle("bg-white", idx === i);
      dot.classList.toggle("bg-white/45", idx !== i);
    });
  };

  dots.forEach((dot, idx) => {
    dot.addEventListener("click", () => {
      index = idx;
      showSlide(index);
    });
  });

  showSlide(index);

  setInterval(() => {
    index = (index + 1) % slides.length;
    showSlide(index);
  }, 4200);
};

const initDateInputs = () => {
  const dateInputs = Array.from(
    document.querySelectorAll("input[type='date']"),
  );
  if (!dateInputs.length) return;

  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, "0");
  const dd = String(today.getDate()).padStart(2, "0");
  const minDate = `${yyyy}-${mm}-${dd}`;

  dateInputs.forEach((input) => {
    input.setAttribute("min", minDate);
  });

  const start = document.querySelector("[data-start-date]");
  const end = document.querySelector("[data-end-date]");

  if (start && end) {
    start.addEventListener("change", () => {
      if (start.value) {
        end.min = start.value;
        if (end.value && end.value < start.value) {
          end.value = start.value;
        }
      }
    });
  }
};

const setYear = () => {
  const target = document.querySelector("[data-year]");
  if (!target) return;
  target.textContent = String(new Date().getFullYear());
};

const initLightbox = () => {
  const overlay = document.querySelector("[data-lightbox]");
  const image = document.querySelector("[data-lightbox-image]");
  if (!overlay || !image) return;

  const gallery = overlay
    .closest("body")
    .querySelector("[data-lightbox-gallery]");
  const triggers = gallery
    ? Array.from(gallery.querySelectorAll("[data-lightbox-trigger]"))
    : [];
  let index = 0;

  const show = (i) => {
    index = (i + triggers.length) % triggers.length;
    const trigger = triggers[index];
    image.setAttribute("src", trigger.getAttribute("data-lightbox-src"));
    image.setAttribute("alt", trigger.getAttribute("data-lightbox-alt") || "");
  };

  const open = (i) => {
    show(i);
    overlay.classList.remove("hidden");
    overlay.classList.add("flex");
    document.body.classList.add("overflow-hidden");
  };

  const close = () => {
    overlay.classList.add("hidden");
    overlay.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
    image.setAttribute("src", "");
  };

  triggers.forEach((trigger, i) => {
    trigger.addEventListener("click", () => open(i));
  });

  overlay
    .querySelector("[data-lightbox-close]")
    ?.addEventListener("click", close);
  overlay
    .querySelector("[data-lightbox-prev]")
    ?.addEventListener("click", () => show(index - 1));
  overlay
    .querySelector("[data-lightbox-next]")
    ?.addEventListener("click", () => show(index + 1));

  overlay.addEventListener("click", (event) => {
    if (event.target === overlay) close();
  });

  document.addEventListener("keydown", (event) => {
    if (overlay.classList.contains("hidden")) return;
    if (event.key === "Escape") close();
    if (event.key === "ArrowLeft") show(index - 1);
    if (event.key === "ArrowRight") show(index + 1);
  });
};

document.addEventListener("DOMContentLoaded", () => {
  initMobileMenu();
  initBannerSlider();
  initDateInputs();
  initLightbox();
  setYear();
});
