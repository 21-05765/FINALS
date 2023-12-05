document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var messages = document.querySelectorAll('.message');
        messages.forEach(function (message) {
            message.style.opacity = '0';
            setTimeout(function () {
                message.remove();
            }, 500);
        });
    }, 3000);
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
