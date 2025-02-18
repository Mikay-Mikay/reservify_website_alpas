const stars = document.querySelectorAll(".star");
const ratingInput = document.getElementById("rating");
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
    // Prevent the default form submission behavior
    event.preventDefault();

    // Check if a rating has been selected
    if (!ratingInput.value) {
        alert("Please select a star rating before submitting!");
        return;
    }

    // Check if the opinion textarea is empty
    const opinionInput = document.querySelector("textarea[name='opinion']");
    if (!opinionInput.value.trim()) {
        alert("Please enter your review message!");
        return;
    }

    // If everything is valid, submit the form
    reviewForm.submit(); // Proceed with form submission
});
