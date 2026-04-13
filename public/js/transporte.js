$(document).ready(function() {
    $("#circuito-choromoro-nav-tab").click();
    $("#san-pedro-but-tab").click();
    
    // Scroll smoothly to the corresponding tab-pane
    $('.nav-pills button').on('click', function () {
        const targetId = $(this).data('bs-target');
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            $('html, body').animate({
                scrollTop: $(targetElement).offset().top - 130
            }, 100); // 500 ms for smooth scrolling
        }
    });
});