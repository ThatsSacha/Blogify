$(function() {
    setLogo();

    function setLogo() {
        $.ajax({
            url: './assets/img/logo.svg',
            method: 'post',
            dataType: "html",
            success: (function(logo) {
                $('aside').append(logo);
            })
        });
    }
});