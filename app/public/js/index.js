

const mobileMenuIcon = document.querySelector('.mobile-menu-icon');
const mobileMenuItem = document.querySelector('.mobile-menu-item');
mobileMenuIcon.addEventListener('click', () => {
    mobileMenuItem.classList.toggle("show");
})