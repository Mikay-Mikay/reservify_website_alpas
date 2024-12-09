<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notification.css">
</head>
<body>
    <h1>Notification Details</h1>
    <div id="notification-content">
        <!-- Notification details will be shown here -->
    </div>
    <a href="reservation.html" class="back-button">Back to Notifications</a>

    <script>
        // Retrieve the query parameter from the URL
        const params = new URLSearchParams(window.location.search);
        const notificationId = params.get("id");

        // Example notifications data (you would likely fetch this from a database in a real app)
        const notifications = {
            1: "HAHAHAHAHAHAHAH.",
            2: "Your schedule has been approved! Visit your calendar to see the updates.",
            2: "Your Reservation is approved proceed to payment.",
        };

        // Display the appropriate notification content based on the ID
        const contentElement = document.getElementById("notification-content");
        if (notificationId && notifications[notificationId]) {
            contentElement.innerHTML = `<p>${notifications[notificationId]}</p>`;
        } else {
            contentElement.innerHTML = "<p>No notification found.</p>";
        }
    </script>
</body>
</html>
