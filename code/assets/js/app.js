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
                showNotification('form.login', 'error', error.responseJSON.message);
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

    $('.login-modal').on('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });

    $('.register-modal').on('click', function(e) {
        e.preventDefault();
        openRegisterModal();
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
                hideNotification('form.login');

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
                        showNotification('form.login', 'error', error.responseJSON.message);
                    }
                })
                .always(function() {
                    hideSpinner('form.login');
                });
            } else {
                showNotification('form.login', 'error', 'Tous les champs sont requis');
            }
        })
    //

    // LOGOUT
        $('body').on('click', 'aside .logout', function() {
            $('.modal-background').addClass('is-active');
            $.ajax({
                method: 'POST',
                url: apiUrl + '/logout',
                success() {
                    window.location.reload();
                    $('.modal-background').removeClass('is-active');
                },
                error(error) {
                    console.log(error);
                }
            });
        });
    //
    
    // REGISTER
        $('body').on('submit', 'form.register', function(e) {
            e.preventDefault();
            const firstName = $('form.register input.first-name');
            const lastName = $('form.register input.last-name');
            const pseudo = $('form.register input.pseudo');
            const mail = $('form.register input.mail');
            const password = $('form.register input.password');
            const passwordConfirm = $('form.register input.password-confirm');

            if (firstName.val().length > 0 && lastName.val().length > 0 && pseudo.val().length > 0 &&  mail.val().length > 0 && password.val().length > 0 && passwordConfirm.val().length > 0) {
                if (password.val() === passwordConfirm.val()) {
                    showSpinner('form.register');
    
                    $.ajax({
                        method: 'POST',
                        url: apiUrl + '/user',
                        headers: {
                            Accept: 'application/json',
                        },
                        dataType: 'json',
                        data: {
                            firstName: firstName.val(),
                            lastName: lastName.val(),
                            pseudo: pseudo.val(),
                            mail: mail.val(),
                            password: password.val()
                        },
                        success(response) {
                            // Wait for hideNotification() ends
                            setTimeout(function() {
                                showNotification('form.register', 'success', 'Inscription terminée !');
                            }, 151);

                            hideNotification('form.register');
                            buildSideBarConnected();
                            $('form.register input').val('');
                            closeModal();
                        },
                        error(error) {
                            showNotification('form.register', 'error', error.responseJSON.message);
                        }
                    })
                    .always(function() {
                        hideSpinner('form.register');
                    });
                } else {
                    showNotification('form.register', 'error', 'Les mots de passe ne correspondent pas');
                }
            } else {
                showNotification('form.register', 'error', 'Tous les champs sont requis');
            }
        });
    //
    
    // UPDATE PROFILE
        $('form.update-profile').on('submit', function(e) {
            e.preventDefault();
            const firstName = $('form.update-profile input.first-name');
            const lastName = $('form.update-profile input.last-name');
            const pseudo = $('form.update-profile input.pseudo');
            const mail = $('form.update-profile input.mail');

            if (firstName.val().length > 0 && lastName.val().length > 0 && pseudo.val().length > 0 &&  mail.val().length > 0) {
                showSpinner('form.update-profile');
                
                $.ajax({
                    method: 'POST',
                    url: apiUrl + '/user/update',
                    headers: {
                        Accept: 'application/json',
                    },
                    dataType: 'json',
                    data: {
                        firstName: firstName.val(),
                        lastName: lastName.val(),
                        pseudo: pseudo.val(),
                        mail: mail.val()
                    },
                    success(response) {
                        // Wait for hideNotification() ends
                        setTimeout(function() {
                            showNotification('form.update-profile', 'success', 'Inscription terminée !');
                        }, 151);
                        
                        hideNotification('form.update-profile');
                        buildSideBarConnected();
                        $('form.register input').val('');
                        closeModal();
                    },
                    error(error) {
                        showNotification('form.update-profile', 'error', error.responseJSON.message);
                    }
                })
                .always(function() {
                    hideSpinner('form.update-profile');
                });
            } else {
                showNotification('form.update-profile', 'error', 'Tous les champs sont requis');
            }
        });
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

    function openRegisterModal() {
        $('.modal-background').addClass('is-active');

        $.ajax({
            url: './assets/inc/register-modal.php',
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
        $('aside ul.secondary__menu').append('<li><a href="profile"><i class="bi bi-person-circle is-active"></i>Profile</a></li><li><a class="logout" href="javascript:;"><i class="bi bi-box-arrow-left is-active"></i></i>Déconnexion</a></li>');
    }

    function showSpinner(element) {
        $(element + ' button i').removeClass('is-active');
        $(element + ' button .spinner-border').addClass('is-active');
    }

    function hideSpinner(element) {
        $(element + ' button .spinner-border').removeClass('is-active');
        $(element + ' button i').addClass('is-active');
    }

    function showNotification(domElement, type, text) {
        $(domElement + ' .notification').removeClass('error');
        $(domElement + ' .notification').removeClass('success');
        $(domElement + ' .notification').addClass(type);

        $(domElement + ' .notification').text(text);
        $(domElement + ' .notification').css('display', 'flex');

        setTimeout(function() {
            $(domElement + ' .notification').addClass('is-active');
        }, 10);
    }

    function hideNotification(domElement) {
        $(domElement + ' .notification').removeClass('is-active');
        $(domElement + ' .notification').removeClass('error');
        $(domElement + ' .notification').removeClass('success');

        setTimeout(function() {
            $(domElement + ' .notification').css('display', 'none');
            $(domElement + ' .notification').empty();
        }, 150);
    }
});