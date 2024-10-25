function toggleNavLarge() {
    const nav = document.getElementById('nav-left');
    if (window.innerWidth >= 1024) {
        nav.classList.toggle('nav-left-hidden');
    }
}

function toggleNavSmall() {
    const nav = document.getElementById('nav-left');
    if (window.innerWidth < 1024) {
        nav.classList.toggle('show');
    }
}

document.addEventListener('click', function(event) {
    const nav = document.getElementById('nav-left');
    const button = document.querySelector('button[onclick="toggleNavSmall()"]');
    if (!nav.contains(event.target) && !button.contains(event.target) && window.innerWidth < 1024) {
        nav.classList.remove('show');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const currentUrl = window.location.href;

    navLinks.forEach(link => {
        // Remove 'active' class from all links
        link.classList.remove('active');
        
        // Check if the href matches the current URL
        if (link.href === currentUrl) {
            // Add 'active' class to the current link
            link.classList.add('active');
        }

        // Add click event listener to each link
        link.addEventListener('click', function() {
            // Remove 'active' class from all links
            navLinks.forEach(link => link.classList.remove('active'));
            // Add 'active' class to the clicked link
            this.classList.add('active');
        });
    });
});



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


//document.addEventListener('DOMContentLoaded', function () {
   // const links = document.querySelectorAll('.nav-link');
   // const loadingOverlay = document.getElementById('loading-overlay');

   // links.forEach(link => {
       // link.addEventListener('click', function (event) {
           // event.preventDefault(); // Prevent default link behavior
            //const href = this.getAttribute('data-href');

           // loadingOverlay.classList.remove('hidden');

            // Simulate a loading delay (adjust or remove this if not needed)
          //  setTimeout(() => {
            //    window.location.href = href;
          //  }, 500); // Adjust delay as necessary
       // });
   // });

    // Handle visibility of loading overlay when the page is loaded
   // window.addEventListener('load', function () {
        //loadingOverlay.classList.add('hidden');
   // });
    
    // Optional: Handle back and forward navigation in single-page applications (SPA)
   // window.addEventListener('popstate', function () {
      //  loadingOverlay.classList.add('hidden');
   // });
//});