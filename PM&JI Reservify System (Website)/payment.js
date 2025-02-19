document.addEventListener("DOMContentLoaded", function () {
    // Payment Validation
    const selectElement = document.getElementById("paymentType");
    const referenceInput = document.getElementById("reference");
    const imageUpload = document.getElementById("imageUpload"); // Image Upload Input

    const paymentType = ["Gcash", "Maya", "Cash"];

    selectElement.innerHTML = '<option value="" disabled selected>Select Payment Method</option>';
    paymentType.forEach(type => {
        const option = document.createElement("option");
        option.value = type;
        option.textContent = type;
        selectElement.appendChild(option);
    });

    function validateReference() {
        let paymentMethod = selectElement.value;
        let refNo = referenceInput.value.trim().toUpperCase(); // Convert to uppercase

        referenceInput.setCustomValidity(""); // Reset errors
        imageUpload.setCustomValidity(""); // Reset errors

        if (paymentMethod === "Gcash") {
            referenceInput.disabled = false;
            referenceInput.required = true;
            referenceInput.setAttribute("maxlength", "13");
            referenceInput.setAttribute("pattern", "\\d{13}");

            if (!/^\d{13}$/.test(refNo)) {
                referenceInput.setCustomValidity("Reference number must be exactly 13 digits for Gcash.");
            }

            // Enable image upload
            imageUpload.required = true;
            imageUpload.disabled = false;
        } else if (paymentMethod === "Maya") {
            referenceInput.disabled = false;
            referenceInput.required = true;
            referenceInput.setAttribute("maxlength", "12");
            referenceInput.setAttribute("pattern", "^[A-Z0-9]{12}$"); // Only uppercase letters and numbers allowed

            referenceInput.value = refNo; // Keep uppercase conversion

            // Validation: Must be exactly 12 characters
            if (refNo.length !== 12) {
                referenceInput.setCustomValidity("Reference number must be exactly 12 characters for Maya.");
            }

            // Enable image upload
            imageUpload.required = true;
            imageUpload.disabled = false;
        } else if (paymentMethod === "Cash") {
            referenceInput.disabled = true;
            referenceInput.required = false; // ✅ Make it NOT required
            referenceInput.value = "";
            referenceInput.removeAttribute("pattern");
            referenceInput.removeAttribute("maxlength");

            // ✅ Disable image upload
            imageUpload.required = false;
            imageUpload.disabled = true;
            imageUpload.value = ""; // Clear any selected file
        }
    }

    selectElement.addEventListener("change", validateReference);
    referenceInput.addEventListener("input", validateReference);

    // Slideshow Functionality
    let currentIndex = 0;
    let slideTimeout;

    function showSlides() {
        clearTimeout(slideTimeout);
        const slides = document.querySelectorAll(".slide");
        const dots = document.querySelectorAll(".dot");

        slides.forEach(slide => (slide.style.display = "none"));
        dots.forEach(dot => dot.classList.remove("active"));

        currentIndex = (currentIndex + 1) > slides.length ? 1 : currentIndex + 1;

        slides[currentIndex - 1].style.display = "block";
        dots[currentIndex - 1].classList.add("active");

        slideTimeout = setTimeout(showSlides, 5000);
    }

    if (document.querySelector(".slide")) {
        showSlides();
    }

    // Notifications
    try {
        const notificationBell = document.querySelector(".notification-bell");
        const notificationDropdown = document.querySelector(".notification-dropdown");

        const notifications = [
            { message: "Your reservation is approved, proceed to <a href='payment.html'>payment</a>.", time: new Date() },
            { message: "You have successfully created your PM&JI Reservify account.", time: new Date() },
        ];

        if (notifications.length > 0) {
            notificationBell.setAttribute("data-count", notifications.length);

            notificationDropdown.innerHTML = notifications.map(notification => `
                <div class="notification-item">
                    ${notification.message}
                    <span class="time">${formatDate(notification.time)}</span>
                </div>
            `).join("");
        } else {
            notificationDropdown.innerHTML = `<p>No new notifications</p>`;
        }

        notificationBell.addEventListener("click", function (e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle("show");
        });

        document.addEventListener("click", function (e) {
            if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.remove("show");
            }
        });

        setInterval(() => {
            const timeElements = document.querySelectorAll(".notification-item .time");
            notifications.forEach((notification, index) => {
                if (timeElements[index]) {
                    timeElements[index].textContent = formatDate(notification.time);
                }
            });
        }, 60000);
    } catch (error) {
        console.warn("Notification system encountered an error:", error);
    }

    function formatDate(date) {
        const now = new Date();
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();

        return now.toDateString() === date.toDateString()
            ? `Today at ${hours}:${minutes}`
            : `${month}/${day}/${year} at ${hours}:${minutes}`;
    }

    // Payment Method Modals
    try {
        const gcashHelp = document.querySelector('#gcashHelp');
        const mayaHelp = document.querySelector('#mayaHelp');
        const gcashModal = document.querySelector('#gcashModal');
        const mayaModal = document.querySelector('#mayaModal');
        const closeGcash = document.querySelector('#closeGcash');
        const closeMaya = document.querySelector('#closeMaya');

        if (gcashHelp && mayaHelp) {
            gcashHelp.addEventListener('click', (e) => {
                e.preventDefault();
                gcashModal.style.display = 'block';
            });

            mayaHelp.addEventListener('click', (e) => {
                e.preventDefault();
                mayaModal.style.display = 'block';
            });

            closeGcash.addEventListener('click', () => gcashModal.style.display = 'none');
            closeMaya.addEventListener('click', () => mayaModal.style.display = 'none');

            window.addEventListener('click', (e) => {
                if (e.target === gcashModal) gcashModal.style.display = 'none';
                if (e.target === mayaModal) mayaModal.style.display = 'none';
            });

            window.addEventListener("keydown", (e) => {
                if (e.key === "Escape") {
                    gcashModal.style.display = "none";
                    mayaModal.style.display = "none";
                }
            });
        }
    } catch (error) {
        console.warn("Payment modal encountered an error:", error);
    }
});
