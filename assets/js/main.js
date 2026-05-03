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

const initBookingPrefill = () => {
  const packageInput = document.querySelector("[data-package-name]");
  if (!packageInput) return;

  const params = new URLSearchParams(window.location.search);
  const pkg = params.get("package");
  if (pkg) {
    packageInput.value = pkg;
  }
};

const initHomepageSearchRedirect = () => {
  const searchForm = document.querySelector(
    "form[action='outbound-packages.html']",
  );
  if (!searchForm) return;

  searchForm.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!searchForm.checkValidity()) {
      searchForm.reportValidity();
      return;
    }

    const formData = new FormData(searchForm);
    const params = new URLSearchParams();

    formData.forEach((value, key) => {
      if (String(value).trim() !== "") {
        params.set(key, String(value));
      }
    });

    window.location.href = `outbound-packages.html?${params.toString()}`;
  });
};

document.addEventListener("DOMContentLoaded", () => {
  initMobileMenu();
  initBannerSlider();
  initDateInputs();
  setYear();
  initBookingPrefill();
  initHomepageSearchRedirect();
});
