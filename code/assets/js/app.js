$(function() {
    setLogo();

    function setLogo() {
        $.ajax({
            url: './assets/img/logo.svg',
            method: 'post',
            dataType: "html",
            success: (function(logo) {
                $('aside ul.principal__menu').before(logo);
            })
        });
    }

    $('aside a.login').on('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });

    $('body').on('click', 'i.close-modal', function() {
        closeModal();
    });

    function closeModal() {
        $('.modal').removeClass('is-active');

        setTimeout(function() {
            $('.modal-background').removeClass('is-active');
            $('.modal-background .modal').remove();
        }, 250);
    }

    function openLoginModal() {
        $('.modal-background').addClass('is-active');

        $.ajax({
            url: './assets/inc/login-modal.php',
            method: 'post',
            dataType: "html",
            success: (function(modal) {
                $('.modal-background').append(modal);
            })
        });

        setTimeout(function() {
            $('.modal').addClass('is-active');
        }, 250);
    }
});