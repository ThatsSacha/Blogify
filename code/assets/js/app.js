$(function() {
    const apiUrl = 'http://localhost:8000';
    setLogo();
    isConnected();

    function isConnected() {
        $.ajax({
            method: 'POST',
            url: apiUrl + '/is-connected',
            headers: {
                Accept: 'application/json',
            },
            dataType: 'json',
            success(response) {
                if (response['logged']) {
                    buildSideBarConnected();
                }
            },
            error(error) {
                displayError($('form.login .error'), error.responseJSON.message);
            }
        })
    }

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

    // LOGIN
        $('body').on('submit', 'form.login', function(e) {
            e.preventDefault();
            const mail = $('form.login input.mail');
            const password = $('form.login input.password');

            if (mail.val().length > 0 && password.val().length > 0) {
                showSpinner('form.login');
                hideError($('form.login .error'));

                $.ajax({
                    method: 'POST',
                    url: apiUrl + '/login-check',
                    headers: {
                        Accept: 'application/json',
                    },
                    dataType: 'json',
                    data: {
                        mail: mail.val(),
                        password: password.val()
                    },
                    success(response) {
                        buildSideBarConnected();
                        closeModal();
                    },
                    error(error) {
                        displayError($('form.login .error'), error.responseJSON.message);
                    }
                })
                .always(function() {
                    hideSpinner('form.login');
                });
            } else {
                displayError($('form.login .error'), 'Tous les champs sont requis');
            }
        })
    //
    
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

    function buildSideBarConnected() {
        $('aside ul.secondary__menu').empty();
        $('aside ul.secondary__menu').append('<li><a href="profile"><i class="bi bi-person-circle is-active"></i>Profile</a></li><li><a href="logout"><i class="bi bi-box-arrow-left is-active"></i></i>Déconnexion</a></li>');
    }

    function showSpinner(element) {
        $(element + ' button i').removeClass('is-active');
        $(element + ' button .spinner-border').addClass('is-active');
    }

    function hideSpinner(element) {
        $(element + ' button .spinner-border').removeClass('is-active');
        $(element + ' button i').addClass('is-active');
    }

    function displayError(domElement, text) {
        domElement.text(text);
        domElement.css('display', 'flex');

        setTimeout(function() {
            domElement.addClass('is-active');
        }, 10);
    }

    function hideError(domElement) {
        domElement.removeClass('is-active');

        setTimeout(function() {
            domElement.css('display', 'none');
            domElement.empty();
        }, 150);
    }
});