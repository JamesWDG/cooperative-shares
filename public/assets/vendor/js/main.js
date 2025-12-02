

// Side bar Active Script

// const sidebarLinks = document.querySelectorAll("ul li a");

// sidebarLinks.forEach((link) => {
//   link.addEventListener("click", function () {
//     sidebarLinks.forEach((l) => l.classList.remove("active"));
//     this.classList.add("active");

//     localStorage.setItem("activeSidebar", this.href);
//   });
// });

// window.addEventListener("DOMContentLoaded", () => {
//   const activeHref = localStorage.getItem("activeSidebar");
//   if (activeHref) {
//     sidebarLinks.forEach((link) => {
//       if (link.href === activeHref) {
//         link.classList.add("active");
//       } else {
//         link.classList.remove("active");
//       }
//     });
//   }
// });

document.addEventListener('click', function (e) {
  const isToggle = e.target.classList.contains('toggle-dropdown');
  const allMenus = document.querySelectorAll('.dropdown-menu');

  allMenus.forEach(menu => menu.style.display = 'none'); // Close all first

  if (isToggle) {
    const dropdown = e.target.nextElementSibling;
    dropdown.style.display = 'block';
    e.stopPropagation();
  }
});

// Close dropdown on outside click
document.addEventListener('click', function () {
  const allMenus = document.querySelectorAll('.dropdown-menu');
  allMenus.forEach(menu => menu.style.display = 'none');
});

