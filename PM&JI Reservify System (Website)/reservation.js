let currentIndex = 0;

function showSlides() {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");

    // Hide all slides and remove active class from dots
    slides.forEach(slide => (slide.style.display = "none"));
    dots.forEach(dot => dot.classList.remove("active"));

    // Show current slide and highlight corresponding dot
    currentIndex++;
    if (currentIndex > slides.length) currentIndex = 1;
    slides[currentIndex - 1].style.display = "block";
    dots[currentIndex - 1].classList.add("active");

    setTimeout(showSlides, 5000); // Change slide every 5 seconds
}

function currentSlide(index) {
    currentIndex = index - 1;
    showSlides();
}

function changeSlide(n) {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");

    currentIndex += n;
    if (currentIndex < 1) currentIndex = slides.length;
    if (currentIndex > slides.length) currentIndex = 1;

    slides.forEach(slide => (slide.style.display = "none"));
    dots.forEach(dot => dot.classList.remove("active"));

    slides[currentIndex - 1].style.display = "block";
    dots[currentIndex - 1].classList.add("active");
}

document.addEventListener("DOMContentLoaded", showSlides);

// for reservation of Event types:
document.addEventListener("DOMContentLoaded", function() {
    // Define the event options
    const eventTypes = [
        "Wedding",
        "Reunion",
        "Baptism",
        "Birthday",
        "Company Event",
        "Others"
    ];

    // Get the select element and the textarea container
    const selectElement = document.getElementById("eventType");
    const otherEventBox = document.getElementById("otherEventBox");

    // Loop through the event types and create an option for each
    eventTypes.forEach(function(event) {
        const option = document.createElement("option");
        option.value = event.toLowerCase().replace(/\s+/g, ''); // Convert to lowercase and remove spaces for value
        option.textContent = event;
        selectElement.appendChild(option);
    });

    // Add an event listener to show/hide the textarea based on selection
    selectElement.addEventListener("change", function() {
        if (selectElement.value === "others") {
            // Show the textarea when 'Others' is selected
            otherEventBox.style.display = "block";
        } else {
            // Hide the textarea when any other option is selected
            otherEventBox.style.display = "none";
        }
    });
});

//for notification function
document.addEventListener("DOMContentLoaded", function () {
    const notificationBell = document.querySelector(".notification-bell");
    const notificationDropdown = document.querySelector(".notification-dropdown");

    // Example notifications array
    const notifications = [
        {
            message: "Your reservation is approved, proceed to <a href='payment.php'>payment</a>.",
            time: new Date()
        },
        { 
            message: "You have successfully created your PM&JI Reservify account.", 
            time: new Date() 
        },
    ];

    // Update the notification count badge
    if (notifications.length > 0) {
        notificationBell.setAttribute("data-count", notifications.length);

        // Populate dropdown with notifications
        const dropdownContent = notifications
            .map(
                notification => `
                    <div class="notification-item">
                        ${notification.message}
                        <span class="time">${formatDate(notification.time)}</span>
                    </div>`
            )
            .join("");
        notificationDropdown.innerHTML = dropdownContent;
    } else {
        notificationDropdown.innerHTML = `<p>No new notifications</p>`;
    }

    // Toggle dropdown visibility on click
    notificationBell.addEventListener("click", function (e) {
        e.stopPropagation(); // Prevent click from bubbling to the document
        notificationBell.classList.toggle("active");
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function () {
        if (notificationBell.classList.contains("active")) {
            notificationBell.classList.remove("active");
        }
    });

    // Update time in real-time
    setInterval(() => {
        const timeElements = document.querySelectorAll(".notification-item .time");
        notifications.forEach((notification, index) => {
            if (timeElements[index]) {
                timeElements[index].textContent = formatDate(notification.time);
            }
        });
    }, 60000); // Update every minute

    // Helper function to format date and time
    function formatDate(date) {
        const now = new Date();
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0"); // Months are 0-indexed
        const year = date.getFullYear();

        if (now.toDateString() === date.toDateString()) {
            return `Today at ${hours}:${minutes}`;
        }
        return `${month}/${day}/${year} at ${hours}:${minutes}`;
    }
});
