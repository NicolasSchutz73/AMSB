document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu');
    const navbarLinks = document.getElementById('navbar-links');
    const barFistMenu = document.querySelector('.menu-toggle > div:nth-child(1)');
    const barSecondMenu = document.querySelector('.menu-toggle > div:nth-child(2)');
    const barThirdMenu = document.querySelector('.menu-toggle > div:nth-child(3)');

    mobileMenuButton.addEventListener('click', function () {
        // voir le menu
        navbarLinks.classList.toggle('show');
        // système de croix et d'hamburger (menu)
        barFistMenu.classList.toggle('active');
        barSecondMenu.classList.toggle('active');
        barThirdMenu.classList.toggle('active');

    });
});

document.addEventListener("DOMContentLoaded", function () {
    const formSteps = document.querySelectorAll(".form-step");
    const nextButtons = document.querySelectorAll(".next");
    const prevButtons = document.querySelectorAll(".prev");

    nextButtons.forEach(button => {
        button.addEventListener("click", function () {
            const currentStep = button.closest(".form-step");
            const nextStepNumber = parseInt(currentStep.dataset.step) + 1;

            // Cache l'étape actuelle
            currentStep.classList.remove("active");

            // Affiche l'étape suivante
            const nextStep = document.querySelector(`.form-step[data-step="${nextStepNumber}"]`);
            nextStep.classList.add("active");
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener("click", function () {
            const currentStep = button.closest(".form-step");
            const prevStepNumber = parseInt(currentStep.dataset.step) - 1;

            // Cache l'étape actuelle
            currentStep.classList.remove("active");

            // Affiche l'étape précédente
            const prevStep = document.querySelector(`.form-step[data-step="${prevStepNumber}"]`);
            prevStep.classList.add("active");
        });
    });
});

