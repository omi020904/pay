document.querySelectorAll(".button").forEach(function(button) {
    button.addEventListener("click", function() {
        const targetUrl = button.getAttribute("data-url");
        window.location.href = targetUrl;
    });
});
