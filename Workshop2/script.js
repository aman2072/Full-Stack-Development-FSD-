/* ================= MOBILE MENU ================= */
const menuButton = document.getElementById("menu-button");
const navLinks = document.querySelector(".nav-links");

function toggleMenu() {
    navLinks.classList.toggle("open");

    const isExpanded = navLinks.classList.contains("open");
    menuButton.setAttribute("aria-expanded", isExpanded);

    menuButton.innerHTML = isExpanded ? "✕" : "☰";
}

menuButton.addEventListener("click", toggleMenu);

/* Close menu when link is clicked (mobile) */
document.querySelectorAll(".nav-links a").forEach(link => {
    link.addEventListener("click", () => {
        if (navLinks.classList.contains("open")) {
            toggleMenu();
        }
    });
});

/* ================= CONTACT FORM ================= */
const contactForm = document.getElementById("contact-form-id");
const messageDiv = document.getElementById("form-message");

contactForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;

    if (name === "" || email === "") {
        messageDiv.textContent = "Please fill all required fields.";
        messageDiv.style.color = "red";
    } else {
        messageDiv.textContent = "Thank you! Your message has been sent.";
        messageDiv.style.color = "green";
        contactForm.reset();
    }
});
