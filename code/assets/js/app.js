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
                        location.reload();
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

    // ADD ARTICLE
        $('form.add-article').on('submit', function(e) {
            e.preventDefault();
            const title = $('form.add-article input.title');
            const teaser = $('form.add-article input.teaser');
            const content = $('form.add-article textarea.content');
            const coverCredit = $('form.add-article input.cover-credit');

            if (title.val().length > 0 && teaser.val().length > 0 && content.val().length > 0 &&  coverCredit.val().length > 0) {
                showSpinner('form.add-article');

                const formData = new FormData();
                formData.append('title', title.val());
                formData.append('teaser', teaser.val());
                formData.append('cover', $('form.add-article input.cover')[0].files[0]);
                formData.append('coverCredit', coverCredit.val());
                formData.append('content', content.val());

                $.ajax({
                    method: 'POST',
                    url: apiUrl + '/add-article',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success(response) {
                        // Wait for hideNotification() ends
                        setTimeout(function() {
                            showNotification('form.add-article', 'success', 'Article ajouté !');
                        }, 151);
                        
                        hideNotification('form.add-article');
                        buildSideBarConnected();
                        $('form.add-article input').val('');
                        closeModal();
                    },
                    error(error) {
                        showNotification('form.add-article', 'error', error);
                    }
                })
                .always(function() {
                    hideSpinner('form.add-article');
                });
            } else {
                showNotification('form.add-article', 'error', 'Tous les champs sont requis');
            }
        });
    //

    // UPDATE ARTICLE
        $('form.update-article').on('submit', function(e) {
            e.preventDefault();
            const title = $('form.update-article input.title');
            const teaser = $('form.update-article input.teaser');
            const content = $('form.update-article textarea.content');
            const coverCredit = $('form.update-article input.cover-credit');

            if (title.val().length > 0 && teaser.val().length > 0 && content.val().length > 0 &&  coverCredit.val().length > 0) {
                showSpinner('form.update-article');

                const formData = new FormData();
                formData.append('title', title.val());
                formData.append('teaser', teaser.val());
                formData.append('cover', $('form.update-article input.cover')[0].files[0]);
                formData.append('coverCredit', coverCredit.val());
                formData.append('content', content.val());

                let searchParams = new URLSearchParams(window.location.search);
                let id = searchParams.get('id');

                $.ajax({
                    method: 'POST',
                    url: apiUrl + '/update-article?id=' + id,
                    processData: false,
                    contentType: false,
                    data: formData,
                    success(response) {
                        // Wait for hideNotification() ends
                        setTimeout(function() {
                            showNotification('form.update-article', 'success', 'Article modifié !');
                        }, 151);
                        
                        hideNotification('form.update-article');
                        closeModal();
                    },
                    error(error) {
                        showNotification('form.update-article', 'error', error);
                    }
                })
                .always(function() {
                    hideSpinner('form.update-article');
                });
            } else {
                showNotification('form.update-article', 'error', 'Tous les champs sont requis');
            }
        });
    //

    // ADD COMMENT
        $('main.article button.add-comment').on('click', function() {
            $('main.article form').toggleClass('is-active');
        });

        $('main.article form').on('submit', function(e) {
            e.preventDefault();
            const content = $('main.article form textarea');

            if (content.val().length) {
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id');

                if (urlParams.has('id') && Number.isInteger(parseInt(id))) {
                    hideNotification('form');
                    showSpinner('form');

                    $.ajax({
                        method: 'POST',
                        url: apiUrl + '/add-comment',
                        Accept: 'application/json',
                        dataType: 'application/json',
                        data: {
                            articleId: id,
                            comment: content.val()
                        }
                    })
                    .always(function() {
                        hideSpinner('form');
                        $('form textarea').val('');

                        setTimeout(() => {
                            showNotification('form', 'success', 'Votre commentaire a bien été envoyé, celui-ci doit être validé par un administrateur.');
                        }, 150);
                    });
                } else {
                    showNotification('form', 'error', 'Une erreur s\'est produite en lien avec l\'ID de l\'article');
                }
            } else {
                showNotification('form', 'error', 'Vous devez saisir un commentaire');
            }
        });
    //

    // VALIDATE COMMENT
        $('main .comment-list button.validate-btn').on('click', function(e) {
            const articleId = $('main .comment-list button.validate-btn').attr('data-article');
            const commentId = $('main .comment-list button.validate-btn').attr('data-comment');
            const btn = this;

            $.ajax({
                method: 'GET',
                url: apiUrl + '/validate-comment?article_id=' + articleId + '&comment_id=' + commentId,
                Accept: 'application/json',
                contentType: 'application/json',
                success() {
                    btn.remove();
                }
            });
        });
    //

    // DELETE ARTICLE
        $('body').on('click', 'button.delete-article', function() {
            openDeleteArticleModal();
        });

        $('body').on('click', 'button.cancel-delete-article', function() {
            closeModal();
        });

        $('body').on('click', 'button.confirm-delete-article', function() {
            showSpinner('.modal');
            const articleId = $('main button.delete-article').attr('data-article');

            $.ajax({
                method: 'DELETE',
                url: apiUrl + '/delete-article?id=' + articleId,
                Accept: 'application/json',
                contentType: 'application/json',
                success: function() {
                    hideSpinner('.modal');
                    location.replace('/blog');
                }
            });
        });
    //

    // CONTACT
        $('main form.contact button.submit').on('click', function(e) {
            e.preventDefault();
            const firstName = $('form.contact input.first-name');
            const lastName = $('form.contact input.last-name');
            const mail = $('form.contact input.mail');
            const subject = $('form.contact select.subject').find(':selected');
            const message = $('form.contact textarea.message');

            if (firstName.val().length > 0 && lastName.val().length > 0 && mail.val().length > 0 &&  message.val().length > 0 && subject.val() > 0) {
                hideNotification('form.contact');
                showSpinner('form.contact');

                const data = {
                    firstName: firstName.val(),
                    lastName: lastName.val(),
                    mail: mail.val(),
                    subject: subject.text(),
                    message: message.val()
                };

                $.ajax({
                    method: 'POST',
                    url: apiUrl + '/send-message',
                    data: data,
                    success: function() {
                        setTimeout(() => {
                            showNotification('form.contact', 'success', 'Votre message a bien été envoyé !');
                            $('form input').val('');
                            $('form textarea').val('');
                        }, 150);
                    },
                    error: function() {
                        setTimeout(() => {
                            showNotification('form.contact', 'error', 'Une erreur s\'est produite lors de l\'envoi de votre message...');
                        }, 150);
                    }
                }).always(function() {
                    hideSpinner('form.contact');
                });
            } else {
                showNotification('form.contact', 'error', 'Tous les champs doivent être remplis');
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

    function openDeleteArticleModal() {
        $('.modal-background').addClass('is-active');

        $.ajax({
            url: './assets/inc/delete-article-modal.php',
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