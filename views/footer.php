    <footer>

        <p class="copyright">© 2025 - Tous droits réservés - <a href="/quiz_night/index.php" 
        aria-label="Accéder à la page d'accueil">QuizNight !</a></p>

    </footer>
</body>
    <script>
        // Selecting the hamburger menu button
        const menuHamburger = document.querySelector("#menu-hamburger");

        // Selecting the navigation links container
        const navLinks = document.querySelector(".nav-link");

        // Adding a click event listener to toggle the mobile menu class
        menuHamburger.addEventListener("click", () => {
            navLinks.classList.toggle("mobile-menu");
        });
    </script>
</html>