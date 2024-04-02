$(document).ready(function () {
    // Get the current page URL
    var currentPage = window.location.href;

    // Loop through each navigation link
    $("nav a").each(function () {
        // Get the href attribute of the link
        var linkUrl = $(this).attr('href');

        // Check if the current page URL matches the link URL
        if (currentPage.indexOf(linkUrl) !== -1) {

            // Add 'active' class to the link
            $(this).addClass('active');
        }
    });
});