



/*dropdown*/
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dropdown visibility on button click
    document.querySelectorAll('.dropbtn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation();  // Prevent event from bubbling up
            var dropdownContent = this.nextElementSibling;
            
            // Hide all dropdowns except the one being clicked
            document.querySelectorAll('.dropdown-content').forEach(function(dropdown) {
                if (dropdown !== dropdownContent) {
                    dropdown.style.display = 'none';
                }
            });
            
            // Toggle the clicked dropdown
            dropdownContent.style.display = (dropdownContent.style.display === 'block') ? 'none' : 'block';
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', function(event) {
        if (!event.target.matches('.dropbtn') && !event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(function(dropdown) {
                dropdown.style.display = 'none';
            });
        }
    });
});









document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.header-fixed');
    const scrollThreshold = 50; // Adjust this value as needed

    function handleScroll() {
        if (window.scrollY > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll);
});




 

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById("menu-toggle");
    const mobileNavLinks = document.getElementById("nav-links");

    // Toggle the mobile menu
    menuToggle.addEventListener("click", function () {
        mobileNavLinks.classList.toggle("show");
    });
});


