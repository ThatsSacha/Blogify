<form class="contact">
    <?php
        if ($this->url === '/contact') {
            ?>
                <div class="card-container">
                    <div class="card">
                        <h2>📍 Localisé</h2>
                        <span>À Marseille</span>
                    </div>
                    <div class="card">
                        <h2>📞 Au téléphone</h2>
                        <span>06 00 00 00 00</span>
                    </div>
                    <div class="card">
                        <h2>📧 Par mail</h2>
                        <span>hello@blogify.sacha-cohen.fr</span>
                    </div>
                </div>
            <?php
        }
    ?>
    <div class="input-container">
        <input type="text" class="first-name" placeholder="Votre prénom">
        <input type="text" class="last-name" placeholder="Votre nom">
    </div>
    <div class="input-container">
        <input type="email" class="mail" placeholder="Votre adresse mail">
    </div>
    <select class="subject">
        <option value="0" disabled selected>Sujet du message</option>
        <option value="1">Informations à propos des compétences</option>
        <option value="2">Simple contact</option>
        <option value="3">Bug & tech</option>
    </select>
    <textarea class="message" placeholder="Faites preuve d'inspiration..."></textarea>
    <div class="notification"></div>
    <button class="red-btn submit">
        <i class="bi bi-envelope-open is-active"></i>
        <div class="spinner-border"></div>
        Envoyer
    </button>
</form>