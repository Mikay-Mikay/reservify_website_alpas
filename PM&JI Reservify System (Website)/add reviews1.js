const stars = document.querySelectorAll(".star");
const ratingInput = document.getElementById("rating");
const feedbackPopup = document.getElementById("feedbackPopup");
const closeBtn = document.querySelector(".close-btn");
const reviewForm = document.querySelector("form");

stars.forEach((star, index) => {
    star.addEventListener("click", () => {
        let value = index + 1; // 1-based index for rating
        ratingInput.value = value; // Set hidden input value

        // Update star colors
        stars.forEach((s, i) => {
            s.classList.remove("bxs-star");
            s.classList.add("bx-star");
            if (i < value) {
                s.classList.add("bxs-star"); // Filled star
                s.classList.remove("bx-star");
            }
        });
    });
});

// Handle form submission
reviewForm.addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    if (!ratingInput.value) {
        alert("Please select a star rating before submitting!");
        return;
    }


});

