const stars = document.querySelectorAll(".star");
const ratingInput = document.getElementById("rating");

stars.forEach(star => {
    star.addEventListener("click", () => {
        let value = star.getAttribute("data-value");
        ratingInput.value = value; // Set hidden input value

        // Update star colors
        stars.forEach(s => s.classList.remove("bxs-star"));
        for (let i = 0; i < value; i++) {
            stars[i].classList.add("bxs-star"); // Filled star
        }
    });
});
