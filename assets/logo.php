            <div class="top-left">
                <div class="logo" onclick="window.location.href='./'">
                    <img src="assets/images/logo_faded_clean.png">
                    <?php if (isMobile($userAgent) == false) {?>
                    <div class="logo-name">
                        <h1><?=skybyn("name")?></h1>
                        <p><?=skybyn("slogan")?></p>
                    </div>
                    <?php }?>
                </div>
            </div>